let apiDomain = '/api/'
/**
 *
 * @param {string} url
 */
let rootUrl = url => '/' + ( url || '' )
let apiRootUrl = url => apiDomain + ( url || '' )

export const CONSTANTS = {
    facebook: 'itsefintech-facebook',
    twitter: 'itsefintech-twitter',
    instagram: 'itsefintech-instagram',
    facebookMessenger: 'itsefintech-facebook-messenger',
    linkedin: 'itsefintech-linkedin',
    snapchat: 'itsefintech-snapchat',
}

export const siteRootUrl = rootUrl()

export const siteMedia = rootUrl( 'media' )
export const siteMediaNews = rootUrl( 'media/news' )
export const siteMediaVideos = rootUrl( 'media/videos' )
export const siteMediaGallery = rootUrl( 'media/gallery' )
export const siteInvestorRelations = rootUrl( 'investor-relations' )

export const siteAboutUs = rootUrl( 'about-us' )

export const siteAboutCareer = rootUrl( 'about-us/career' )
export const siteAboutApply = rootUrl( 'about-us/career/submit-resume' )
export const sitePrivacy = rootUrl( 'privacy-policy' )
export const siteTerms = rootUrl( 'terms-and-conditions' )
export const siteContactUs = rootUrl( 'contact-us' )


/**
 * API endpoints
 */

export const siteTestimonials = apiRootUrl( 'testimonials' )
export const siteFAQ = apiRootUrl( 'faq' )
export const siteContact = apiRootUrl( 'contact' )
