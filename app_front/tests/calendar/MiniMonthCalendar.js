import React, { Component } from 'react'
import PropTypes from 'prop-types'
import MiniMonthCalendarHeader from "./MiniMonthCalendarHeader"
import MiniMonthCalendarGrid from "./MiniMonthCalendarGrid"



export default class extends Component {

    static defaultProps = {
        year: new Date().getFullYear(),
        month: new Date().getMonth()+1,
        onMonthChange: e => {}
    }

    constructor(props) {
        super(props)
    }


    render() {
        return (
            <div className="MiniMonthCalendar">
                <MiniMonthCalendarHeader {...this.props} />
                <MiniMonthCalendarGrid {...this.props} />
            </div>
        )
    }

}