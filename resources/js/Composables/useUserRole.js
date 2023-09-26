import { usePage } from '@inertiajs/inertia-vue3';

export default function () {
    // pass in array of roles to check for
    // e.g. hasRole('admin', 'superadmin');
    function hasRole(...roles) {
        if (!roles) return false;
        return usePage().props?.value?.user?.roles?.some(
            (role) => roles?.indexOf(role) >= 0
        );
    }
    return {
        hasRole,
    };
}
