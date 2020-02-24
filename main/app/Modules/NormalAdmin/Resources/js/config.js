export const CONSTANTS = {
    APP_NAME: 'Itse FinTech',
}

const baseUrl = '/backend/'
const apiBaseUrl = `${baseUrl}api/`

export const adminLoginEndpoint = baseUrl + 'login'
export const adminViewAdmins = apiBaseUrl + 'admins'
export const adminCreateAdmin = apiBaseUrl + 'admin/create'
export const adminAdminPermissions = id => apiBaseUrl + 'admin/' + id + '/permissions'
export const adminSuspendAdmin = id => apiBaseUrl + 'admin/' + id + '/suspend'
export const adminDeleteAdmin = id => apiBaseUrl + 'admin/' + id + '/delete'
export const adminRestoreAdmin = id => apiBaseUrl + 'admin/' + id + '/restore'
export const adminViewUsers = apiBaseUrl + 'users'
export const adminDeleteUser = id => apiBaseUrl + 'user/' + id + '/delete'
export const adminViewUserTransactions = id => apiBaseUrl + 'user/' + id + '/transactions'
export const getTransactionsSummary = id => apiBaseUrl + 'user/' + id + '/transactions/summary'
export const adminCreateUserTransaction = apiBaseUrl + 'transaction/create'
export const adminUpdateUserTransaction = apiBaseUrl + 'transaction/update'
export const adminDeleteTransaction = id => apiBaseUrl + 'transaction/' + id + '/delete'
export const adminViewTestimonials = apiBaseUrl + 'testimonials'
export const adminCreateTestimonial = apiBaseUrl + 'testimonial/create'
export const adminUpdateTestimonial = apiBaseUrl + 'testimonial/update'
export const adminDeleteTestimonial = id => apiBaseUrl + 'testimonial/' + id + '/delete'
// export const adminDeleteWithdrawalRequest = id => apiBaseUrl + 'withdrawal-request/' + id + '/delete'
// export const adminChangeVerificationStatus = apiBaseUrl + 'user/verify'
export const adminViewLatestWithdrawalRequestsSummary = apiBaseUrl + 'transactions/withdrawals/summary'
export const adminViewLatestActivitiesSummary = apiBaseUrl + 'activities/summary'
export const adminDeleteActivity = id => apiBaseUrl + 'activity/' + id + '/delete'
export const adminDashboardInfoData = apiBaseUrl + 'dashboard/statistics'

export const normalAdminApproveLoanRequest = id => apiBaseUrl + 'loan-request/' + id + '/approve'
