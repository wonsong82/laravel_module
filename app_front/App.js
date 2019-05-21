import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { LocaleProvider } from 'antd'
import enUS from 'antd/lib/locale-provider/en_US'
import koKR from 'antd/lib/locale-provider/ko_KR'
import MiniMonthCalendar from "./tests/calendar/MiniMonthCalendar";


import './tests/calendar/calendar.scss'
import Scheduler from "./tests/Scheduler";

export default class extends Component {

    static defaultProps = {}

    constructor(props) {
        super(props)
        this.state = {
            value: ''
        }
    }



    render() {
        return (
            <LocaleProvider locale={enUS}>

                <Scheduler/>

            </LocaleProvider>
        )
    }

}