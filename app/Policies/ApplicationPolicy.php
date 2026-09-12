<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\Citizen;
use App\Models\Staff;

class ApplicationPolicy
{
    /**
     * Determine whether the citizen can view the application.
     */
    public function viewCitizen(Citizen $citizen, Application $application): bool
    {
        return $citizen->id === $application->citizen_id;
    }

    /**
     * Determine whether the staff can view the application (ward-scoped).
     */
    public function viewStaff(Staff $staff, Application $application): bool
    {
        // Palika admin can view all within palika
        if ($staff->isAdmin()) {
            return $staff->palika_id === $application->palika_id;
        }

        // Staff can only view applications assigned to their ward
        return $staff->ward_id === $application->ward_id;
    }

    /**
     * Determine whether the staff can review / update status.
     */
    public function updateStaff(Staff $staff, Application $application): bool
    {
        return $this->viewStaff($staff, $application);
    }

    /**
     * Determine whether the staff can give final approval and issue recommendation letter.
     */
    public function approve(Staff $staff, Application $application): bool
    {
        if (!$this->viewStaff($staff, $application)) {
            return false;
        }

        return $staff->canApproveApplications();
    }
}
