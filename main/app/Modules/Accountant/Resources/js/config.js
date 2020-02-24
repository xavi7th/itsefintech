export const CONSTANTS = {
    APP_NAME: 'Itse FinTech',
}

const baseUrl = '/accountant/'
const apiBaseUrl = `${baseUrl}api/`

export const adminAdminPermissions = id => apiBaseUrl + 'admin/' + id + '/permissions'
export const accountantConfirmDebitCardRequestPayment = id => apiBaseUrl + 'debit-card-request/' + id + '/paid/confirm'
