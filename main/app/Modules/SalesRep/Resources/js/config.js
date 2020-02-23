export const CONSTANTS = {
    APP_NAME: 'Itse FinTech',
}

const baseUrl = '/sales-reps/'
const apiBaseUrl = `${baseUrl}api/`

export const salesRepLoginEndpoint = baseUrl + 'login'
export const salesRepViewAdmins = apiBaseUrl + 'admins'
export const salesRepCreateAdmin = apiBaseUrl + 'admin/create'
export const salesRepAdminPermissions = id => apiBaseUrl + 'admin/' + id + '/permissions'


/** Sales Reps routes */
export const getSalesRepStatistics = apiBaseUrl + 'statistics'
export const requestCardStock = apiBaseUrl + 'debit-card/request'
export const salesRepAllocateDebitCardToCardUser = id => `${apiBaseUrl}debit-card/${id}/allocate`
