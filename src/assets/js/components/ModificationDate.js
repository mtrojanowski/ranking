import React, { Component } from 'react';

export default class ModificationDate extends Component {
    render() {
        const { lastModified } = this.props;
        const showModificationDate = lastModified !== undefined && lastModified !== null && lastModified !== "";
        return (<div className="modification-date">
            {showModificationDate && <div>
                ostatnio zmodyfikowany: { lastModified }
            </div>}
        </div>);
    }
}
