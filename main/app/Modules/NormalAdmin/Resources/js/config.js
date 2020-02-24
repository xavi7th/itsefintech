export const CONSTANTS = {
    APP_NAME: 'Itse FinTech',
}

const baseUrl = '/backend/'
const apiBaseUrl = `${baseUrl}api/`

export const normalAdminApproveLoanRequest = id => apiBaseUrl + 'loan-request/' + id + '/approve'
export const normalAdminCreateDebitCard = `${apiBaseUrl}debit-card/create`
export const normalAdminAssignDebitCard = id => `${apiBaseUrl}debit-card/${id}/assign`
