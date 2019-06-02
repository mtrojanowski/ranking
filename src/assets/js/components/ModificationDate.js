import React, { Component } from 'react';
import PropTypes from 'prop-types';

export default class ModificationDate extends Component {
    render() {
        const { lastModified } = this.props;
        const showModificationDate = lastModified !== null;
        return (<div className="modification-date">
            {showModificationDate && <div>
                Ranking ostatnio zmodyfikowany: {{ lastModified }}
            </div>}
        </div>);
    }
}

ModificationDate.propTypes = {
    rankingLastModified: PropTypes.string.isRequired()
};
