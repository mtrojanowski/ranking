import React, { Component } from 'react';

import { getFutureTournaments, getPreviousTournaments, getRank } from '../services/tournaments';
import TournamentsTable from "./TournamentsTable";

export default class PreviousTournaments extends Component {
    constructor(props) {
        super(props);
        this.state = {
            tournaments: []
        };
    }

    componentDidMount() {
        if (this.state.tournaments.length === 0) {
            Promise.resolve()
                .then(() => getPreviousTournaments())
                .then((tournaments) => this.setState({ tournaments }));
        }
    }

    render() {
        return (
                <TournamentsTable title="Turnieje minione" tournaments={this.state.tournaments} />
        );
    }
}
