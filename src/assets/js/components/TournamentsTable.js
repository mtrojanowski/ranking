import React, { Component } from 'react';
import shortId from 'shortid';

import { getRank } from '../services/tournaments';

export default class TournamentsTable extends Component {

    render() {

        return (
            <div>
                <h2>{this.props.title}</h2>
                <table className="table table-striped mt-4">
                    <thead className="thead-dark">
                    <tr>
                        <th>Identyfikator</th>
                        <th>Data turnieju</th>
                        <th>Nazwa</th>
                        <th>Miasto, miejsce</th>
                        <th>Organizator</th>
                        <th>Ranga</th>
                        <th>Punkty</th>
                        <th>Typ</th>
                        <th>www</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.props.tournaments.map(tournament => {
                        const rank = getRank(tournament.rank, tournament.type);
                        const type = tournament.type === 'single' ? 'Single' : 'Drużynowy';

                        return (<tr key={shortId()}>
                            <th scope="row">{tournament.legacyId}</th>
                            <td>{tournament.date}</td>
                            <td>{tournament.name}</td>
                            <td>{tournament.town}, {tournament.venue}</td>
                            <td>{tournament.organiser}</td>
                            <td>{rank}</td>
                            <td>{tournament.points}</td>
                            <td>{type}</td>
                            <td><a href={tournament.rulesUrl} target="_blank">Strona</a></td>
                            <td>-</td>
                        </tr>);
                    })}
                    </tbody>
                </table>
            </div>
            );
    }
}
