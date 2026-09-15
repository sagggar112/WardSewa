# Deploy WardSewa on Netlify + Supabase

WardSewa is now a static Vite application. Netlify hosts the client and
Supabase provides email/password authentication and PostgreSQL. The old Laravel runtime
is not used by this deployment.

## 1. Create the Supabase backend

1. Create a new Supabase project.
2. In **SQL Editor**, run [`supabase/schema.sql`](supabase/schema.sql).
3. Supabase's built-in Email provider is enabled by default. Add the Netlify URL
   to Authentication → URL Configuration when the site has been deployed. Keep
   email confirmation enabled for production.
4. Copy the project URL and the **anon public** key from Project Settings → API.
   Do not use the `service_role` key in Netlify.

## 2. Deploy the site on Netlify

1. Import this Git repository in Netlify.
2. Netlify reads `netlify.toml`: it runs `npm run build` and publishes `dist`.
3. In **Site configuration → Environment variables**, add the two values in
   `.env.netlify.example` using the values from Supabase.
4. Deploy. The SPA fallback is supplied by `static/_redirects`.

## What moved and what remains

The client includes the public service catalogue, published notices, phone OTP
sign-in, and the citizen application entry point. The Laravel code remains in
the repository only as a migration reference; it is not published or executed
by Netlify. Staff workflows, payments, PDF certificates, document upload, and
administrative dashboards require follow-up client views plus server-side
Supabase Edge Functions for privileged actions.
