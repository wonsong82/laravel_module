import React from 'react'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import { combineReducers } from 'redux'



export const reducer = combineReducers({
    ...require('./reducers/data')
})