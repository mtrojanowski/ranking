import React, { Component } from 'react';

import {getTournament} from "../services/tournaments";

export default class TournamentResults extends Component {
    constructor(props) {
        super(props);
        this.state = {
            tournament: null,
            tournamentId: props.tournamentId
        };
    }

    componentDidMount() {
        if (this.state.tournament === null) {
            Promise.resolve()
                .then(() => getTournament(this.state.tournamentId))
                .then((tournament) => this.setState({ tournament }));
        }
    }

    render() {
        const tournament = this.state.tournament;
        const showTournamentData = tournament !== null;
        const venueString = showTournamentData && tournament.venue !== null && tournament.venue !== '' ? (', ' + tournament.venue) : '';
        let i = 0;

        return (
            <div>
            {showTournamentData && <div>
                <h4>Wyniki turnieju <strong>{tournament.name} <span className="tournament-data-id">ID: {tournament.legacyId}</span> </strong></h4>
                <h6>Data: {tournament.date}, miejsce: {tournament.town}{venueString}</h6>
                <table className="table table-striped mt-4">
                    <thead className="thead-dark">
                    <tr>
                        <th>Miejsce</th>
                        <th>Armia</th>
                        <th>Imię</th>
                        <th>Nick</th>
                        <th>Klub</th>
                        <th>Miasto</th>
                        <th>Punkty</th>
                        <th />
                    </tr>
                    </thead>
                    <tbody>
                    {tournament.results.map(position => {
                        return (<tr key={i++}>
                            <th scope="row">{position.place}</th>
                            <td>{position.army}</td>
                            <td>{position.playerFirstName}</td>
                            <td>{position.playerNickname}</td>
                            <td>{position.playerAssociation}</td>
                            <td>{position.playerTown}</td>
                            <td>{position.points}</td>
                            <td>
                                {position.judge === 1 && <span>Sędzia główny</span>}
                                {position.judge === 2 && <span>Sędzia liniowy</span>}
                            </td>
                        </tr>);
                    })}
                    </tbody>
                </table>
            </div>}
            {!showTournamentData && <div>...</div>}
            </div>
            );
    }
}
