import React, { Component } from 'react';
import shortId from 'shortid';

import { getRank } from '../services/tournaments';
import {Link} from "react-router-dom";

export default class TournamentsTable extends Component {

    render() {

        return (
            <div>
                <h2>{this.props.title}</h2>
                <table className="table table-striped mt-4">
                    <thead className="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Data</th>
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
                        const type = tournament.type === 'single' ? 'Single' : tournament.playersInTeam === 2 ? 'Parówka' : 'Drużynowy (' + tournament.playersInTeam + ')';
                        const status = tournament.status === 'OK' ? 'Zaliczony' : '-';
                        const showResultsLink = tournament.status === 'OK';

                        return (<tr key={shortId()}>
                            <th scope="row">{tournament.legacyId}</th>
                            <td>{tournament.date}</td>
                            <td>
                                {!showResultsLink && <span>{tournament.name}</span>}
                                {showResultsLink && <span>
                                    <Link to={'/tournament/' + tournament.id}>{tournament.name}</Link>
                                </span>}
                            </td>
                            <td>{tournament.town}, {tournament.venue}</td>
                            <td>{tournament.organiser}</td>
                            <td>{rank}</td>
                            <td>{tournament.points}</td>
                            <td>{type}</td>
                            <td><a href={tournament.rulesUrl} target="_blank">Strona</a></td>
                            <td>{status}</td>
                        </tr>);
                    })}
                    </tbody>
                </table>
            </div>
            );
    }
}
