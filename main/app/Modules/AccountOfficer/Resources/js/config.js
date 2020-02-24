export const CONSTANTS = {
    APP_NAME: 'Itse FinTech',
}

const baseUrl = '/account-officers/'
const apiBaseUrl = `${baseUrl}api/`

export const adminAdminPermissions = id => apiBaseUrl + 'admin/' + id + '/permissions'
export const accountOfficeAllocateVoucherToRequest = id => `${apiBaseUrl}voucher-request/${id}/allocate`
export const accountOfficeApproveVoucherRequest = id => apiBaseUrl + 'voucher-request/' + id + '/approve'
