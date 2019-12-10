export const CONSTANTS = {
    APP_NAME: 'Itse FinTech',
}

const baseUrl = '/accountant/'
const apiBaseUrl = `${baseUrl}api/`

export const adminLoginEndpoint = baseUrl + 'login'
export const adminViewAdmins = apiBaseUrl + 'admins'
export const adminCreateAdmin = apiBaseUrl + 'admin/create'
export const adminAdminPermissions = id => apiBaseUrl + 'admin/' + id + '/permissions'
