import './vendor/vendor'
import React from 'react'
import { render } from 'react-dom'
import { Provider } from 'react-redux'
import { createStore, applyMiddleware } from 'redux'
import { reducer } from './routes'



// error handling
if( process.env.NODE_ENV === 'production'){ window.onerror = () => true }




// Middlewares /////////////////////////////////////////////////////////

const Middleware = {
    thunk: require('redux-thunk').default,
    reduxLogger: require('redux-logger').createLogger()
}

const middlewares =
    process.env.NODE_ENV === 'production'?
        applyMiddleware( Middleware.thunk ):
        applyMiddleware( Middleware.thunk, Middleware.reduxLogger )




// Store ////////////////////////////////////////////////////////////////

const store = createStore(reducer, {}, middlewares)




// Render ///////////////////////////////////////////////////////////////
import App from './App'


const start = () => {
    render (
        <Provider store={store}>
            <App/>
        </Provider>,
        document.querySelector('#react-root')
    )
}


if(module.hot) module.hot.accept()
if(process.env.NODE_ENV === 'production')
    start()
else {
    setTimeout(start, 500)
}

