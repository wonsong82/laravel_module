import React, { Component } from 'react'
import PropTypes from 'prop-types'



export default class extends Component {

    static defaultProps = {
        title: ''
    }

    constructor(props) {
        super(props)
    }

    render() {
        const { detail, style } = this.props

        return (
            <div className="Event" style={style}
                 data-id={detail.id}
                 onMouseDown={this.props.onMouseDown}
            >
                { detail.title }
            </div>
        )
    }

}