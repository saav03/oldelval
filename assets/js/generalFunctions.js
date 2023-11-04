function isObject(sth){
    return (typeof sth === 'object' && !Array.isArray(sth) && sth !== null)
}

function isFunction(sth){
    return typeof sth === 'function'
}

function isArray(sth){
	return Array.isArray(sth);
}