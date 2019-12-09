let apiDomain = '/api/'
/**
 *
 * @param {string} url
 */
let rootUrl = url => '/' + ( url || '' )
let apiRootUrl = url => apiDomain + ( url || '' )

export const CONSTANTS = {
	facebook: 'amju-facebook',
	twitter: 'amju-twitter',
	instagram: 'amju-instagram',
	facebookMessenger: 'amju-facebook-messenger',
	linkedin: 'amju-linkedin',
	snapchat: 'amju-snapchat',
}

export const siteRootUrl = rootUrl()
export const sitePersonalBanking = rootUrl( 'personal-banking-account-types' )
export const siteAmjuFlexiSavings = rootUrl( 'personal-banking/amju-flexi-savings-account' )
export const siteAmjuEdusaveSavings = rootUrl( 'personal-banking/amju-edusave-savings-account' )
export const siteThriftSavings = rootUrl( 'personal-banking/amju-thrift-savings-account' )
export const siteMarriageSavings = rootUrl( 'personal-banking/amju-marriage-savings-account' )
export const siteChristmsaSavings = rootUrl( 'personal-banking/amju-christmas-account-savings' )
export const siteEsusuSavings = rootUrl( 'personal-banking/amju-esusu-savings-account' )
export const siteEnterpreneurSavings = rootUrl( 'personal-banking/amju-enterpreneur-savings-account' )
export const sitePBAmjuAccount = rootUrl( 'personal-banking/amju-account' )
export const sitePBServices = rootUrl( 'personal-banking/services' )
export const sitePBCards = rootUrl( 'personal-banking/cards' )
export const sitePBLoans = rootUrl( 'personal-banking/loans' )
export const sitePBWaysToBank = rootUrl( 'personal-banking/ways-to-bank' )
export const siteSMEBanking = rootUrl( 'small-and-medium-enterprise-banking' )
export const sitePlatinumCurrent = rootUrl( 'sme/platinum-current-account' )
export const siteCorporateCurrent = rootUrl( 'sme/coporate-current-account' )
export const siteOverdraftCurrent = rootUrl( 'sme/overdraft-current-account' )
export const siteDivineCurrent = rootUrl( 'sme/divine-current-account' )
export const siteCooperativeCurrent = rootUrl( 'sme/cooperative-current-account' )
export const siteGroupLeadingCurrent = rootUrl( 'sme/group-leading-current-account' )
export const siteCBBankAccount = rootUrl( 'corporate-banking/bank-account' )
export const siteCBLoansInvestment = rootUrl( 'corporate-banking/loans-and-investment' )
export const siteEbankingServices = rootUrl( 'amju-electronic-banking-services' )
export const siteEBAtmServices = rootUrl( 'amju-electronic-banking-services/atm' )
export const siteEBMobileBankingServices = rootUrl( 'amju-electronic-banking-services/mobile-banking' )
export const siteEBInternetBankingServices = rootUrl( 'amju-electronic-banking-services/internet-banking' )
export const siteEBPosServices = rootUrl( 'amju-electronic-banking-services/pos' )
export const siteEBUssdCodesServices = rootUrl( 'amju-electronic-banking-services/ussd-codes' )
export const siteEBNipServices = rootUrl( 'amju-electronic-banking-services/nip' )
export const siteEBProductsAndServices = rootUrl( 'products-and-services' )
export const siteMedia = rootUrl( 'media' )
export const siteMediaNews = rootUrl( 'media/news' )
export const siteMediaVideos = rootUrl( 'media/videos' )
export const siteMediaGallery = rootUrl( 'media/gallery' )
export const siteInvestorRelations = rootUrl( 'investor-relations' )
export const siteIRFinInfo = rootUrl( 'investor-relations/financial-information' )
export const siteIRInvestorNews = rootUrl( 'investor-relations/investor-news' )
export const siteIROutlookInsights = rootUrl( 'investor-relations/outlook-and-insights' )
export const siteIREvents = rootUrl( 'investor-relations/events' )
export const siteIRShareholders = rootUrl( 'investor-relations/shareholders' )
export const siteIRInformation = rootUrl( 'investor-relations/information' )
export const siteAboutUs = rootUrl( 'about-us' )
export const siteAboutBranches = rootUrl( 'about-us/our-branches' )
export const siteAboutOurCompany = rootUrl( 'about-us/our-company' )
export const siteAboutCorpGov = rootUrl( 'about-us/corporate-governance' )
export const siteAboutBizFocus = rootUrl( 'about-us/business-focus' )
export const siteAboutAwards = rootUrl( 'about-us/awards-and-achievements' )
export const siteAboutCareer = rootUrl( 'about-us/career' )
export const siteAboutApply = rootUrl( 'about-us/career/submit-resume' )
export const sitePrivacy = rootUrl( 'privacy-policy' )
export const siteTerms = rootUrl( 'terms-and-conditions' )
export const siteContactUs = rootUrl( 'contact-us' )
export const siteCreateAccount = rootUrl( 'account/create' )
export const siteCreateAccountSuccess = rootUrl( 'account/create/success' )


/**
 * API endpoints
 */
export const siteInternetBanking = 'https://ibank.amjuuniquemfbng.com/'
export const siteTestimonials = apiRootUrl( 'testimonials' )
export const siteFAQ = apiRootUrl( 'faq' )
export const siteContact = apiRootUrl( 'contact' )
export const siteCreateAccountApi = apiRootUrl( 'account/create' )
