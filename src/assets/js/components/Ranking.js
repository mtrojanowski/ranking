import React, { Component } from 'react';

import { getRanking } from '../services/ranking';
import {Link} from "react-router-dom";

export default class Ranking extends Component {
    constructor(props) {
        super(props);
        this.state = {
            ranking: []
        };
    }

    componentDidMount() {
        if (this.state.ranking.length === 0) {
            Promise.resolve()
                .then(() => getRanking())
                .then((ranking) => this.setState({ ranking }));
        }
    }

    render() {
        let lp = 1;

        return (
            <div>
                <h2>Ranking</h2>
                <table className="table table-striped mt-4">
                    <thead className="thead-dark">
                    <tr>
                        <th>Lp.</th>
                        <th>Imię</th>
                        <th>Nick</th>
                        <th>Klub</th>
                        <th>Miasto</th>
                        <th>Suma</th>
                        <th>Turnieje</th>
                        <th>Ind.</th>
                    </tr>
                    </thead>
                    <tbody>
                    {this.state.ranking.map(position => {
                        return (<tr key={position.id}>
                            <th scope="row">{lp++}</th>
                            <td>{position.player.firstName}</td>
                            <td>{position.player.nickname}</td>
                            <td>{position.player.association}</td>
                            <td>{position.player.town}</td>
                            <td>{position.points}</td>
                            <td>{position.tournamentCount}</td>
                            <td><Link
                                to={"/ranking/individual/" + position.player.legacyId}
                                className="nav-link">Wyniki
                            </Link></td>
                        </tr>);
                    })}
                    </tbody>
                </table>
            </div>
            );
    }
}
