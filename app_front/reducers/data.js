export const data = (state = {
    loaded: false,
    data: {}
}, action) => {
    switch(action.type){

        case 'data.loaded':
            const loaded = action.payload
            return {...state, loaded}

        case 'data.data':
            const data = action.payload || {}
            return {...state, ...data}

        default: return state
    }
}


