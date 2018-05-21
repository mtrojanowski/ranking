import React, { Component } from 'react';

import { getFutureTournaments } from '../services/tournaments';
import TournamentsTable from "./TournamentsTable";

export default class FutureTournaments extends Component {
    constructor(props) {
        super(props);
        this.state = {
            tournaments: []
        };
    }

    componentDidMount() {
        if (this.state.tournaments.length === 0 || this.props.refreshTournaments === true) {
            Promise.resolve()
                .then(() => getFutureTournaments())
                .then((tournaments) => this.setState({ tournaments }));
        }
    }

    render() {
        return (
                <TournamentsTable title="Turnieje nadchodzące" tournaments={this.state.tournaments}/>
        );
    }
}
