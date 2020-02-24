export const CONSTANTS = {
    APP_NAME: 'Itse FinTech',
}

const apiBaseUrl = '/admin-panel/api/'
const baseUrl = '/admin-panel/'

export const adminLoginEndpoint = baseUrl + 'login'
export const adminViewAdmins = apiBaseUrl + 'admins'
export const adminCreateAdmin = apiBaseUrl + 'admin/create'
export const adminAdminPermissions = id => apiBaseUrl + 'admin/' + id + '/permissions'
export const adminSuspendAdmin = id => apiBaseUrl + 'admin/' + id + '/suspend'
export const adminDeleteAdmin = id => apiBaseUrl + 'admin/' + id + '/delete'
export const adminRestoreAdmin = id => apiBaseUrl + 'admin/' + id + '/restore'
export const adminViewNormalAdmins = apiBaseUrl + 'normal-admins'
export const adminCreateNormalAdmin = apiBaseUrl + 'normal-admin/create'
export const adminNormalAdminPermissions = id => apiBaseUrl + 'normal-admin/' + id + '/permissions'
export const adminSuspendNormalAdmin = id => apiBaseUrl + 'normal-admin/' + id + '/suspend'
export const adminDeleteNormalAdmin = id => apiBaseUrl + 'normal-admin/' + id + '/delete'
export const adminRestoreNormalAdmin = id => apiBaseUrl + 'normal-admin/' + id + '/restore'
export const adminViewAccountants = apiBaseUrl + 'accountants'
export const adminCreateAccountant = apiBaseUrl + 'accountant/create'
export const adminAccountantPermissions = id => apiBaseUrl + 'accountant/' + id + '/permissions'
export const adminSuspendAccountant = id => apiBaseUrl + 'accountant/' + id + '/suspend'
export const adminDeleteAccountant = id => apiBaseUrl + 'accountant/' + id + '/delete'
export const adminRestoreAccountant = id => apiBaseUrl + 'accountant/' + id + '/restore'
export const adminViewAccountOfficers = apiBaseUrl + 'account-officers'
export const adminCreateAccountOfficer = apiBaseUrl + 'account-officer/create'
export const adminAccountOfficerPermissions = id => apiBaseUrl + 'account-officer/' + id + '/permissions'
export const adminSuspendAccountOfficer = id => apiBaseUrl + 'account-officer/' + id + '/suspend'
export const adminDeleteAccountOfficer = id => apiBaseUrl + 'account-officer/' + id + '/delete'
export const adminRestoreAccountOfficer = id => apiBaseUrl + 'account-officer/' + id + '/restore'
export const adminViewCardAdmins = apiBaseUrl + 'card-admins'
export const adminCreateCardAdmin = apiBaseUrl + 'card-admin/create'
export const adminCardAdminPermissions = id => apiBaseUrl + 'card-admin/' + id + '/permissions'
export const adminSuspendCardAdmin = id => apiBaseUrl + 'card-admin/' + id + '/suspend'
export const adminDeleteCardAdmin = id => apiBaseUrl + 'card-admin/' + id + '/delete'
export const adminRestoreCardAdmin = id => apiBaseUrl + 'card-admin/' + id + '/restore'
export const adminViewCustomerSupports = apiBaseUrl + 'customer-supports'
export const adminCreateCustomerSupport = apiBaseUrl + 'customer-support/create'
export const adminCustomerSupportPermissions = id => apiBaseUrl + 'customer-support/' + id + '/permissions'
export const adminSuspendCustomerSupport = id => apiBaseUrl + 'customer-support/' + id + '/suspend'
export const adminDeleteCustomerSupport = id => apiBaseUrl + 'customer-support/' + id + '/delete'
export const adminRestoreCustomerSupport = id => apiBaseUrl + 'customer-support/' + id + '/restore'
export const adminViewDispatchAdmins = apiBaseUrl + 'dispatch-admins'
export const adminCreateDispatchAdmin = apiBaseUrl + 'dispatch-admin/create'
export const adminDispatchAdminPermissions = id => apiBaseUrl + 'dispatch-admin/' + id + '/permissions'
export const adminSuspendDispatchAdmin = id => apiBaseUrl + 'dispatch-admin/' + id + '/suspend'
export const adminDeleteDispatchAdmin = id => apiBaseUrl + 'dispatch-admin/' + id + '/delete'
export const adminRestoreDispatchAdmin = id => apiBaseUrl + 'dispatch-admin/' + id + '/restore'
export const adminViewSalesReps = apiBaseUrl + 'sales-reps'
export const adminCreateSalesRep = apiBaseUrl + 'sales-rep/create'
export const adminSalesRepPermissions = id => apiBaseUrl + 'sales-rep/' + id + '/permissions'
export const adminSuspendSalesRep = id => apiBaseUrl + 'sales-rep/' + id + '/suspend'
export const adminDeleteSalesRep = id => apiBaseUrl + 'sales-rep/' + id + '/delete'
export const adminRestoreSalesRep = id => apiBaseUrl + 'sales-rep/' + id + '/restore'
export const adminViewCardUsers = apiBaseUrl + 'card-users'
export const adminCreateCardUser = apiBaseUrl + 'card-user/create'
export const adminCardUserPermissions = id => apiBaseUrl + 'card-user/' + id + '/permissions'
export const adminSuspendCardUser = id => apiBaseUrl + 'card-user/' + id + '/suspend'
export const adminDeleteCardUser = id => apiBaseUrl + 'card-user/' + id + '/delete'
export const adminRestoreCardUser = id => apiBaseUrl + 'card-user/' + id + '/restore'
export const adminSetCardUserCreditLimit = id => apiBaseUrl + 'card-user/' + id + '/credit-limit'
export const adminSetCardUserMerchantLimit = id => apiBaseUrl + 'card-user/' + id + '/merchant-limit'
export const adminShowFullBvnNumber = id => apiBaseUrl + 'card-user/' + id + '/bvn'


/**
 * Merchant Routes
 * @param {id} id int
 */

export const adminViewMerchantCategories = apiBaseUrl + 'merchant-categories'
export const adminEditMerchantCategory = id => apiBaseUrl + 'merchant-category/' + id
export const adminCreateMerchantCategory = apiBaseUrl + 'merchant-category'
export const adminViewMerchants = apiBaseUrl + 'merchants'
export const adminCreateMerchant = apiBaseUrl + 'merchant/create'
export const adminSuspendMerchant = id => apiBaseUrl + 'merchant/' + id + '/suspend'
export const adminRestoreMerchant = id => apiBaseUrl + 'merchant/' + id + '/restore'
export const adminDeleteMerchant = id => apiBaseUrl + 'merchant/' + id + '/delete'

/**
 * Card Routes
 */
export const adminViewDebitCards = id => !id ? `${apiBaseUrl}debit-cards` : `${apiBaseUrl}debit-cards/${id}`
export const adminCreateDebitCard = `${apiBaseUrl}debit-card/create`
export const adminAssignDebitCard = id => `${apiBaseUrl}debit-card/${id}/assign`
export const toggleDebitCardSuspension = id => apiBaseUrl + 'debit-card/' + id + '/suspension'
export const adminShowFullPANNumber = id => apiBaseUrl + 'debit-card/' + id + '/pan'


/**
 * Debit Card Type Routes
 */
export const adminViewDebitCardTypes = `${apiBaseUrl}debit-card-types`
export const adminCreateDebitCardType = `${apiBaseUrl}debit-card-type/create`
export const adminEditDebitCardType = id => `${apiBaseUrl}debit-card-type/${id}`




export const adminViewDebitCardRequests = apiBaseUrl + 'debit-card-requests'


export const adminViewDebitCardStockRequests = apiBaseUrl + 'stock-requests'
export const adminMarkDebitCardStockRequestAsProcessed = id => apiBaseUrl + 'stock-request/' + id + '/processed'


/** Sales Reps routes */
export const getSalesRepStatistics = apiBaseUrl + 'statistics'
export const requestCardStock = apiBaseUrl + 'debit-card/request'


/** Card Funding routes */
export const adminViewDebitCardFundingRequests = apiBaseUrl + 'debit-card-funding-requests'
export const adminMarkDebitCardFundingRequestAsProcessed = id => apiBaseUrl + 'debit-card-funding-request/' + id + '/process'

/** Loans */
export const adminViewLoanRequests = apiBaseUrl + 'loan-requests'
export const adminMarkLoanRequestAsPaid = id => apiBaseUrl + 'loan-request/' + id + '/paid'
export const adminViewLoanTransactions = apiBaseUrl + 'loan-transactions'
export const adminViewVouchers = apiBaseUrl + 'vouchers'
export const adminCreateVoucher = apiBaseUrl + 'voucher/create'
export const adminViewVoucherRequests = apiBaseUrl + 'voucher-requests'
export const adminApproveVoucherRequest = id => apiBaseUrl + 'voucher-request/' + id + '/approve'
export const adminMarkVoucherRequestAsPaid = id => apiBaseUrl + 'voucher-request/' + id + '/paid'
export const adminViewVoucherTransactions = apiBaseUrl + 'voucher-transactions'
export const adminAllocateVoucherToRequest = id => `${apiBaseUrl}voucher-request/${id}/allocate`



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
export const adminViewLatestWithdrawalRequestsSummary = apiBaseUrl + 'transactions/withdrawals/summary'
export const adminViewLatestActivitiesSummary = apiBaseUrl + 'activities/summary'
export const adminDeleteActivity = id => apiBaseUrl + 'activity/' + id + '/delete'
export const adminDashboardInfoData = apiBaseUrl + 'dashboard/statistics'
