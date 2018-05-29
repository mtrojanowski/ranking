import React, { Component } from 'react';
import shortId from 'shortid';

import { getRanking } from '../services/ranking';

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
                            <td>{position.player.town}</td>
                            <td>{position.points}</td>
                            <td>{position.tournamentsIncluded} / {position.tournamentsCount}</td>
                            <td>-</td>
                        </tr>);
                    })}
                    </tbody>
                </table>
            </div>
            );
    }
}