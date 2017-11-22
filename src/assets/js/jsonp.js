import coreJsonp from 'jsonp'
export default function jsonp(url = '', params = {}, opts = {}, fn) {
    var query = url.indexOf('?') === -1 ? '?' : '&'
    query += Object
        .keys(params)
        .map(key => key + '=' + encodeURIComponent(params[key]))
        .join('&')
    return coreJsonp(`${url}${query}`, opts, fn)
}
