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
        const newTournamentId = this.props.location.state.newTournamentId;
        const tournamentWasAdded = newTournamentId > 0;
        return (
            <div>
            {tournamentWasAdded && <div className="alert alert-success" role="alert">
                Turniej poprawnie dodany. Jego identyfikator: {newTournamentId}.
            </div>}
                <TournamentsTable title="Turnieje nadchodzące" tournaments={this.state.tournaments}/>
            </div>
        );
    }
}
