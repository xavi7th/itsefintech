export const CONSTANTS = {
    APP_NAME: 'Itse FinTech',
}

const baseUrl = '/customer-supports/'
const apiBaseUrl = `${baseUrl}api/`

export const customerSupportCreateSupportTicket = apiBaseUrl + 'support-ticket/create'
export const adminAcceptSupportTicket = id => apiBaseUrl + 'support-ticket/' + id + '/accept '
export const adminMarkSupportTicketResolved = id => apiBaseUrl + 'support-ticket/' + id + '/resolved'
export const customerSupportCloseTicket = id => apiBaseUrl + 'support-ticket/' + id + '/close'
