export const moneyFormat = val => {
    if(val && !isNaN(val)){
        return '$' + parseFloat(val).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')
    }
    return '$0.00'
}

export const numberFormat = val => {
    if(val){
        val = val.toString()
        if(val.indexOf('.') != -1)
            return val.toString().replace(/(\d)(?=(\d{3})+\.)/g, '$1,')
        else
            return val.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1,')
    }
    return val
}

export const toPercent = rate => {
    if(rate && !isNaN(rate)){
        return parseFloat(rate * 100).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,') + '%'
    }
    return '0.00%'
}


export const clearFloat = n => {
    return n ? parseFloat(parseFloat(n).toFixed(2)): ''
}

export const nl2br = str => {
    var breakTag = '<br />';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}


export const moneyNumber = val => {
    if(val && !isNaN(val)){
        return parseFloat(val).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1,')
    }
    return '0.00'
}