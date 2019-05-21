import React, { Component } from 'react'
import PropTypes from 'prop-types'
import { DatePicker, message, Input } from 'antd'
import moment from 'moment'
/*
 import 'moment/locale/ko'
 moment.locale('ko')
 */


export default class extends Component {

    static defaultProps = {}

    constructor(props) {
        super(props)
        this.state = {
            value: ''
        }
    }


    componentDidMount() {
        socket.on('app-channel:App\\Events\\TestSocketEvent', payload => {
            message.info('event triggered')
        })

        socket.on('app-channel:App\\Events\\TestInputEvent', payload => {
            this.setState({
                value: payload.data
            })
        })
    }


    onDateChange(e){
        const value = e? e.format('YYYY-MM-DD dddd'): ''
        message.info(value)
    }


    onInputChange(e){
        $.post(url(`api/input`), {data:e.target.value});
    }



    render() {
        return (
            <div className="SocketTest">
                <DatePicker
                    onChange={this.onDateChange.bind(this)}
                    placeholder="날짜"
                    value={moment('2015-01-01')}
                />

                <Input
                    onInput={this.onInputChange.bind(this)}
                    value={this.state.value}
                />
            </div>
        )
    }

}