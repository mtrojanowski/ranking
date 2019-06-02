import React, { Component } from 'react';

import { getRanking } from '../services/ranking';
import {Link} from "react-router-dom";
import ModificationDate from "./ModificationDate";

export default class Ranking extends Component {
    constructor(props) {
        super(props);
        this.state = {
            ranking: [],
            seasonId: props.match.params.seasonId,
            army: props.match.params.army,
            rankingLastModified: null
        };
    }

    componentDidMount() {
        Promise.resolve()
            .then(() => getRanking(this.state.seasonId, this.state.army))
            .then((rankingData) => this.setState({ ranking: rankingData, rankingLastModified: rankingData.rankingLastModified }));
    }

    componentWillReceiveProps(nextProps, nextContent) {
        const seasonId = nextProps.match.params.seasonId;
        if (seasonId !== this.state.seasonId) {
            Promise.resolve()
                .then(() => getRanking(seasonId))
                .then((ranking) => this.setState({ seasonId, ranking }));
        }
    }

    render() {
        let lp = 1;
        const { seasonId, ranking, rankingLastModified } = this.state;

        return (
            <div>
                <h2>Ranking</h2>
                <ModificationDate rankingLastModified={rankingLastModified} />
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
                    {ranking.map(position => {
                        return (<tr key={position.id}>
                            <th scope="row">{lp++}</th>
                            <td>{position.player.firstName}</td>
                            <td>{position.player.nickname}</td>
                            <td>{position.player.association}</td>
                            <td>{position.player.town}</td>
                            <td>{position.points}</td>
                            <td>{position.tournamentCount}</td>
                            <td><Link
                                to={"/ranking/" + (seasonId !== undefined ? seasonId + '/' : '') + "individual/" + position.player.legacyId}
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
