import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { Tooltip } from 'antd'
import moment from 'moment'



export default class extends Component {

    static defaultProps = {
        year: new Date().getFullYear(),
        month: new Date().getMonth()+1,
        selected: false,
        today: moment().format('YYYY-MM-DD'),
        onDateClick: e => {}
    }

    constructor(props) {
        super(props)
    }

    onDateClick(e){
        const dateString = $(e.currentTarget).attr('data-date')
        const date = moment(dateString)
        const year = parseInt(date.format('YYYY'))
        const month = parseInt(date.format('MM'))
        const day = parseInt(date.format('DD'))

        this.props.onDateClick({
            date: dateString,
            year, month, day

        })
    }

    render() {
        const { year, month, selected, today } = this.props
        const dayNames = this.getDayNames()
        const daysTable = this.getDays()

        return (
            <div role="grid" className="MiniMonthCalendarGrid">


                <div role="row" className="day-names">

                    { dayNames.map( dayName => (
                    <Tooltip type="bottom" title={dayName.long} key={`${year}-${month}-header-${dayName.index}`} >
                        <span role="column-header" className="day-name">
                            <span className="day-name-text">{dayName.short}</span>
                        </span>
                    </Tooltip>
                    ))}

                </div>


                <div role="row-group" className="days">

                    { daysTable.map( (row, i) => (
                    <div role="row" className="days-row" key={`${year}-${month}-row-${i}`}>

                        { row.map( day => (
                        <span role="grid-cell" className={`day${day.currentMonth?'':' oor'}${day.date===selected?' selected':''}${day.date===today?' today':''}`} key={`${year}-${month}-day-${day.date}`}
                              data-date={day.date}
                              onClick={this.onDateClick.bind(this)}
                        >
                            <span className="day-text">{day.day}</span>
                        </span>
                        ))}


                    </div>
                    ))}

                </div>

            </div>
        )
    }


    getDayNames() {
        const todayInWeek = new Date().getDay()
        const sunday = moment().add((7 - todayInWeek)%7, 'days');

        let columns = []
        for(let i=0; i<7; i++){
            let day = moment(sunday).add(i, 'days').format('dddd')
            columns.push({
                index: i,
                short: day.substring(0, 1),
                long: day
            })
        }

        return columns
    }


    getDays() {
        const { year, month } = this.props

        const numOfDays = this.getNumOfDaysOfMonth(year, month)
        const startDate = new Date(year, month-1, 1)
        const endDate = new Date(year, month-1, numOfDays)
        const numOfDaysToFillBefore = startDate.getDay()
        const numOfDaysToFillAfter = (6 * 7) - (numOfDaysToFillBefore + numOfDays)
        const startDateToFillBefore = moment(startDate).subtract(numOfDaysToFillBefore, 'days')
        const startDateToFillAfter = moment(endDate).add(1, 'days')

        let days = [], i, ym, d

        // fill in prev month
        ym = startDateToFillBefore.format('YYYY-MM-')
        d = parseInt(startDateToFillBefore.format('D'))
        for(i=d; i<d+numOfDaysToFillBefore; i++){
            days.push({
                date: ym + (i<10? '0'+i : i),
                day: i.toString(),
                currentMonth: false
            })
        }

        // fill in this month
        ym = moment(startDate).format('YYYY-MM-')
        for(i=1; i<=numOfDays; i++){
            days.push({
                date:  ym + (i<10? '0'+i : i),
                day: i.toString(),
                currentMonth: true
            })
        }

        // fill in after month
        ym = startDateToFillAfter.format('YYYY-MM-')
        for(i=1; i<=numOfDaysToFillAfter; i++){
            days.push({
                date: ym + (i<10? '0'+i : i),
                day: i.toString(),
                currentMonth: false
            })
        }


        // split them into 7 x 6
        let daysTable = []
        for(i=0; i<days.length; i++){
            if(i%7 === 0){ // add new row
                daysTable.push([])
            }
            let lastRow = daysTable.length-1

            daysTable[lastRow].push(days[i])
        }

        return daysTable
    }


    getNumOfDaysOfMonth(year, month){
        return new Date(year, month, 0).getDate()
    }



}