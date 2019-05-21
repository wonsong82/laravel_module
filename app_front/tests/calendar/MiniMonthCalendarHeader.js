import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Icon, Tooltip } from 'antd'
import moment from 'moment'



export default class extends Component {

    static defaultProps = {
        year: new Date().getFullYear(),
        month: new Date().getMonth()+1,
        monthFormat: 'MMMM YYYY',
        showNavigation: true,
        onMonthChange: e => {}
    }

    constructor(props) {
        super(props)
    }

    onPreviousClick(){
        let { month, year } = this.props
        month = month - 1
        if(month < 1){
            month = 12
            year--
        }

        this.props.onMonthChange({month, year})
    }

    onNextClick(){
        let { month, year } = this.props
        month = month + 1
        if(month > 12){
            month = 1
            year++
        }

        this.props.onMonthChange({month, year})
    }


    render() {
        return (
            <div className="MiniMonthCalendarHeader">
                <span className="month-text">{this.getMonthString()}</span>

                {this.props.showNavigation&&
                <Tooltip placement="bottom" title="Previous Month">
                    <div className="button"
                         onClick={this.onPreviousClick.bind(this)}
                    >
                        <Icon className="icon" type="left" />
                    </div>
                </Tooltip>
                }

                {this.props.showNavigation&&
                <Tooltip placement="bottom" title="Next Month">
                    <div className="button"
                         onClick={this.onNextClick.bind(this)}
                    >
                        <Icon className="icon" type="right" />
                    </div>
                </Tooltip>
                }

            </div>
        )
    }

    getMonthString(){
        const { year, month } = this.props
        const date = new Date(year, month-1, 1)

        return moment(date).format('MMMM YYYY')
    }

}