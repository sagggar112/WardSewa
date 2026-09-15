-- WardSewa's Netlify backend. Run this in Supabase Dashboard > SQL Editor.
-- This schema uses Supabase Auth as the source of identity; never expose a
-- service_role key in Netlify or the browser.

create extension if not exists pgcrypto;

create type public.user_role as enum ('citizen', 'ward_staff', 'ward_chair', 'local_government_admin', 'district_admin', 'super_admin');
create type public.application_status as enum ('submitted', 'under_review', 'documents_requested', 'approved', 'rejected', 'cancelled');

create table public.profiles (
  id uuid primary key references auth.users(id) on delete cascade,
  full_name text,
  phone text unique,
  role public.user_role not null default 'citizen',
  ward_name text,
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now()
);

create table public.service_types (
  code text primary key,
  name_en text not null,
  name_ne text not null,
  category text not null,
  fee numeric(10,2) not null default 0,
  turnaround_days integer not null default 3,
  is_active boolean not null default true
);

insert into public.service_types (code, name_en, name_ne, category) values
  ('FOUR_BOUNDARIES', 'Four Boundaries Recommendation', 'चार किल्ला सिफारिस', 'recommendation'),
  ('RESIDENCE', 'Residence Recommendation', 'बसोबास सिफारिस', 'recommendation'),
  ('UNMARRIED', 'Unmarried Recommendation', 'अविवाहित सिफारिस', 'recommendation'),
  ('BIRTH_REGISTRATION', 'Birth Registration', 'जन्म दर्ता', 'vital_registration'),
  ('COMPLAINT', 'Complaint', 'गुनासो दर्ता', 'complaint')
on conflict (code) do nothing;

create table public.applications (
  id uuid primary key default gen_random_uuid(),
  application_number text unique not null default ('WS-' || upper(substr(replace(gen_random_uuid()::text, '-', ''), 1, 10))),
  citizen_id uuid not null references public.profiles(id) on delete cascade,
  service_code text not null references public.service_types(code),
  form_data jsonb not null default '{}'::jsonb,
  status public.application_status not null default 'submitted',
  remarks text,
  created_at timestamptz not null default now(),
  updated_at timestamptz not null default now()
);

create table public.complaints (
  id uuid primary key default gen_random_uuid(),
  ticket_number text unique not null default ('WC-' || upper(substr(replace(gen_random_uuid()::text, '-', ''), 1, 10))),
  citizen_id uuid references public.profiles(id) on delete set null,
  category text not null default 'other',
  subject text not null,
  description text not null,
  location text,
  status text not null default 'open' check (status in ('open', 'in_progress', 'resolved', 'closed')),
  created_at timestamptz not null default now()
);

create table public.appointments (
  id uuid primary key default gen_random_uuid(),
  citizen_id uuid not null references public.profiles(id) on delete cascade,
  appointment_date date not null,
  time_slot text not null,
  purpose text not null,
  status text not null default 'scheduled' check (status in ('scheduled', 'confirmed', 'rescheduled', 'completed', 'cancelled')),
  created_at timestamptz not null default now()
);

create table public.notices (
  id uuid primary key default gen_random_uuid(),
  title text not null,
  body text not null,
  is_published boolean not null default false,
  published_at timestamptz,
  created_by uuid references public.profiles(id) on delete set null,
  created_at timestamptz not null default now()
);

alter table public.profiles enable row level security;
alter table public.service_types enable row level security;
alter table public.applications enable row level security;
alter table public.complaints enable row level security;
alter table public.appointments enable row level security;
alter table public.notices enable row level security;

create policy "profiles are visible to their owner" on public.profiles for select using (auth.uid() = id);
create policy "citizens update their own profile" on public.profiles for update using (auth.uid() = id);
create policy "services are public" on public.service_types for select using (is_active = true);
create policy "citizens read their applications" on public.applications for select using (auth.uid() = citizen_id);
create policy "citizens create their applications" on public.applications for insert with check (auth.uid() = citizen_id);
create policy "citizens read their complaints" on public.complaints for select using (auth.uid() = citizen_id);
create policy "citizens create complaints" on public.complaints for insert with check (auth.uid() = citizen_id);
create policy "citizens read their appointments" on public.appointments for select using (auth.uid() = citizen_id);
create policy "citizens create appointments" on public.appointments for insert with check (auth.uid() = citizen_id);
create policy "published notices are public" on public.notices for select using (is_published = true);

-- Automatically create the public profile when Auth creates a user.
create or replace function public.handle_new_user()
returns trigger language plpgsql security definer set search_path = public as $$
begin
  insert into public.profiles (id, phone, full_name)
  values (new.id, new.phone, coalesce(new.raw_user_meta_data ->> 'full_name', ''));
  return new;
end;
$$;

create trigger on_auth_user_created
  after insert on auth.users for each row execute procedure public.handle_new_user();
