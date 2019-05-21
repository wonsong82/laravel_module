import React, { Component } from 'react'
import { findDOMNode } from 'react-dom'
import './Scheduler.scss'
import PropTypes from 'prop-types'
import moment from 'moment'
import MiniMonthCalendar from "./calendar/MiniMonthCalendar";
import Event from './Event'

export default class extends Component {

    static defaultProps = {}

    constructor(props) {
        super(props)

        this.state = {
            year: new Date().getFullYear(),
            month: new Date().getMonth()+1,
            selected: false,
            today: moment().format('YYYY-MM-DD'),
            events: [
                {
                    detail: {
                        id: 1,
                        name: 'Won Song',
                        title: 'Event1',
                        start: {hour: 4, min: 0},
                        duration: 60
                    },
                    style: {}
                },
                {
                    detail: {
                        id: 2,
                        name: 'Won Song',
                        title: 'Event2',
                        start: {hour: 6, min: 0},
                        duration: 90
                    },
                    style: {}

                },
                {
                    detail: {
                        id: 3,
                        name: 'Joon Choi',
                        title: 'Event3',
                        start: {hour: 5, min: 30},
                        duration: 45
                    },
                    style: {}
                },
                {
                    detail: {
                        id: 4,
                        name: 'Fred Kim',
                        title: 'Events 4',
                        start: {hour: 13, min: 30},
                        duration: 120
                    },
                    style: {}
                }
            ],
            currentEvent: null,
            currentMovingEvent: null,
            selectedPartition: null
        }

        this.gridPartitionData = []
        this.filledPartitionData = []

    }


    onDateChange(e){
        const { date, year, month } = e
        this.setState({
            /*year, month,*/
            selected: date
        })
        console.log(date)
    }

    onMonthChange(e){
        this.setState({
            year: e.year,
            month: e.month
        })
    }

    getHours(){
        let hours = []

        for(let i=0; i<24; i++){
            const hour = i
            let hourText = ''
            if(hour < 12) hourText = `${i}am`
            else if(hour === 12) hourText = '12pm'
            else if (hour === 24) hourText = '12am'
            else hourText = i-12 + 'pm'

            hours.push({hour, hourText})
        }

        return hours
    }

    getColumns(){
        const people = ['Won Song', 'Fred Kim', 'Joon Choi', 'Dongmin Lee', 'Hyunjae', 'Jae Park']
        return people.map( person => ({ name: person }))
    }

    updateForce = () => {
        this.updateGridPartitionData()
        this.forceUpdate()
    }

    componentDidMount() {
        $(window).on('resize', this.updateForce)
        this.updateForce()

        $(window).on('mousemove', this.onEventMouseMove.bind(this))
        $(window).on('mouseup', this.onEventMouseUp.bind(this))
    }

    componentWillUnmount() {
        $(window).off('resize', this.updateForce)
    }

    updateGridPartitionData() {
        const
            columns = this.getColumns(),
            hours = this.getHours(),
            $this = $(findDOMNode(this)),
            $scheduler = $('.Scheduler', $this)

        let gridPartitionData = []

        hours.map( (hour, i) => {
            columns.map( (column, y) => {
                [0,1,2,3].map( partition => {

                    let
                        element = $(`[data-cell="${i}-${y}"]`, $this),
                        x1 = element.offset().left - $scheduler.offset().left,
                        x2 = x1 + element.innerWidth(),
                        height = element.innerHeight(),
                        y1 = (element.offset().top - $scheduler.offset().top) + ((height/4) * partition),
                        y2 = y1 + (height/4)


                    gridPartitionData.push({
                        element, x1, x2, y1, y2, column, rowNum:i, colNum:y, partNum:partition, rowPartIndex: i*4 + partition,
                        hour: hour.hour, min: partition * (60/4)
                    })
                })
            })
        })

        this.gridPartitionData = gridPartitionData
    }

    updateEvents( events ){
        if(!events.length) return []

        events = events.constructor === Array ? events : [events];

        let gridPartitionData = this.gridPartitionData,
            eventState = []

        let filledPartitionData = []

        events.map( event => {
            let startPartition = gridPartitionData.filter( data => data.hour === event.detail.start.hour && data.min === event.detail.start.min && data.column.name === event.detail.name )

            if(startPartition.length){
                startPartition = startPartition[0]

                let yIncrement = (event.detail.duration / (60/4)) - 1
                let rowNum = startPartition.rowNum,
                    partNum = startPartition.partNum + yIncrement

                rowNum = rowNum + Math.floor(partNum / 4)
                partNum = partNum % 4

                let endPartition = gridPartitionData.filter( data => data.colNum === startPartition.colNum && data.rowNum === rowNum && data.partNum === partNum )

                if(endPartition.length){
                    endPartition = endPartition[0]

                    const rightGap = 5, bottomGap = 2

                    eventState.push({
                            detail: event.detail,
                            style: {
                                left: startPartition.x1,
                                width: startPartition.x2 - startPartition.x1 - rightGap,
                                top: startPartition.y1,
                                height: endPartition.y2 - startPartition.y1 - bottomGap
                            }
                        })

                    // update filledPartitionData
                    let startPartitionIndex = startPartition.rowNum * 4 + startPartition.partNum,
                        endPartitionIndex = endPartition.rowNum * 4 + endPartition.partNum

                    let filledPartitions = gridPartitionData.filter( data => data.colNum === startPartition.colNum && data.rowPartIndex >= startPartitionIndex && data.rowPartIndex <= endPartitionIndex)
                    filledPartitionData = [...filledPartitionData, ...filledPartitions]
                }
            }
        })

        this.filledPartitionData = filledPartitionData

        return eventState;
    }

    onEventMouseDown(e){
        const $this = $(findDOMNode(this))
        const currentEvent = $(e.currentTarget)
        const currentMovingEvent = currentEvent.clone().addClass('moving').appendTo('.events', $this);

        currentEvent.addClass('faded')
        this.setState({currentEvent, currentMovingEvent})
    }

    onEventMouseMove(e){
        const { currentEvent, currentMovingEvent, events } = this.state
        const $this = $(findDOMNode(this)),
              $scheduler = $('.Scheduler', $this)

        if(!currentEvent) return

        const
            x = e.pageX - $scheduler.offset().left,
            y = e.pageY - $scheduler.offset().top

        // find x

        let startPartition = null;
        $.each(this.gridPartitionData, (i, e) => {
            if(x >= e.x1 && x <=e.x2 && y >= e.y1 && y <= e.y2 ){
                startPartition = e
                return false
            }
        })

        if(startPartition){

            // if not collapsible
            const collapsible = true
            let canUpdate = true

            if(collapsible){
                let filledPartitionData = this.filledPartitionData
                let id = parseInt(currentEvent.attr('data-id'))
                let currentEventData = events.filter( event => event.detail.id === id )

                if(currentEventData.length){
                    currentEventData = currentEventData[0]
                    let numOfPartitions = parseInt(currentEventData.detail.duration / 15)
                    const expectedPartitions = this.gridPartitionData.filter( data => data.colNum === startPartition.colNum && data.rowPartIndex >= startPartition.rowPartIndex && data.rowPartIndex <= startPartition.rowPartIndex + numOfPartitions -1 )

                    const currentStartPartition = filledPartitionData.filter( data => data.column.name === currentEventData.detail.name && data.hour === currentEventData.detail.start.hour && data.min === currentEventData.detail.start.min )[0]
                    const currentPartitions = filledPartitionData.filter( data => data.colNum === currentStartPartition.colNum && data.rowPartIndex >= currentStartPartition.rowPartIndex && data.rowPartIndex <= currentStartPartition.rowPartIndex + numOfPartitions - 1 )
                    filledPartitionData = filledPartitionData.filter( filledPart => {
                        return !currentPartitions.filter( currentPart => currentPart.colNum === filledPart.colNum && currentPart.rowPartIndex === filledPart.rowPartIndex ).length
                    })

                    $.each(filledPartitionData, (i, e) => {
                        expectedPartitions.map( partition => {
                            if(partition.colNum === e.colNum && partition.rowPartIndex === e.rowPartIndex){
                                canUpdate = false
                                return false
                            }
                        })
                    })
                }
            }

            if(canUpdate){
                currentMovingEvent.css({
                    left: startPartition.x1,
                    top: startPartition.y1
                })
                this.setState({
                    selectedPartition: startPartition
                })
            }
        }
    }

    onEventMouseUp(e){
        const { currentEvent, currentMovingEvent, selectedPartition, events } = this.state

        if(!currentEvent) return

        currentEvent.removeClass('faded')
        currentMovingEvent.remove()

        let id = parseInt(currentEvent.attr('data-id'))

        this.setState({
            currentEvent: null,
            currentMovingEvent: null,
            selectedPartition: null,
            events: events.map( event => {
                if(event.detail.id === id){
                    return {
                        ...event,
                        detail: {
                            ...event.detail,
                            name: selectedPartition.column.name,
                            start: {
                                hour: selectedPartition.hour,
                                min: selectedPartition.min
                            }
                        }
                    }
                }
                return event
            }),
        })
    }

    render() {
        const { year, month, selected, today } = this.state

        const columns = this.getColumns()
        const hours = this.getHours()
        const events = this.updateEvents(this.state.events)

        return (
            <div className="Frame">
                <header>
                    <h3>Scheduler</h3>
                </header>
                <section>
                    <main>
                        <div className="Scheduler">


                            <div className="header">


                                <div className="time-line"/>

                                {columns.map( (column, i) => (
                                <div className="header-column" key={`header-column-${i}`}>{column.name}</div>
                                ))}

                            </div>



                            <div className="grid">

                                {hours.map( (hour, i) => (
                                <div className="grid-row" data-hour={hour.hour} key={`row-${i}`}>

                                    <div className="grid-cell time-line">
                                        <span className="hour">{hour.hourText}</span>
                                    </div>

                                    {columns.map( (column, y)=> (
                                    <div className="grid-cell" key={`grid-cell-${i}-${y}`} data-cell={`${i}-${y}`} />
                                    ) )}

                                </div>
                                ))}

                            </div>


                            <div className="events">

                                {events.map( (event, i) => (
                                <Event
                                    key={`event-${event.detail.id}`}
                                    {...event}
                                    onMouseDown={this.onEventMouseDown.bind(this)}
                                />
                                ))}

                            </div>


                        </div>
                    </main>



                    <nav>
                        <div style={{width:250}}>
                            <MiniMonthCalendar
                                year={year}
                                month={month}
                                onDateClick={this.onDateChange.bind(this)}
                                onMonthChange={this.onMonthChange.bind(this)}
                                selected={selected}
                                today={today}
                                showNavigation={true}
                            />

                            <MiniMonthCalendar
                                year={year}
                                month={month+1}
                                onDateClick={this.onDateChange.bind(this)}
                                onMonthChange={this.onMonthChange.bind(this)}
                                selected={selected}
                                today={today}
                                showNavigation={false}
                            />

                            <MiniMonthCalendar
                                year={year}
                                month={month+2}
                                onDateClick={this.onDateChange.bind(this)}
                                onMonthChange={this.onMonthChange.bind(this)}
                                selected={selected}
                                today={today}
                                showNavigation={false}
                            />
                        </div>
                    </nav>
                    <aside>Aside</aside>
                </section>
                <footer>Footer</footer>
            </div>
        )
    }

}
