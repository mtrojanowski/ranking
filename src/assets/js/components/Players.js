import React, { Component } from 'react';
import shortId from 'shortid';

import { getPlayers } from '../services/players';

export default class Players extends Component {
    constructor(props) {
        super(props);
        this.state = {
            players: []
        };
    }

    componentDidMount() {
        if (this.state.players.length === 0) {
            Promise.resolve()
                .then(() => getPlayers())
                .then((players) => this.setState({ players }));
        }
    }

    render() {
        return (
            <div>
                <h2>Lista Graczy</h2>
                <table className="table table-striped mt-4">
                    <thead className="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Imię</th>
                            <th>Miasto</th>
                        </tr>
                    </thead>
                    <tbody>
                    {this.state.players.map(player => (<tr key={shortId()}>
                        <th scope="row">{player.legacyId}</th>
                        <td>{player.name}</td>
                        <td>{player.town}</td>
                    </tr>))}
                    </tbody>
                </table>
            </div>);
    }
}
