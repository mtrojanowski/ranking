import React, { Component } from 'react';

import { getRanking } from '../services/ranking';
import {Link} from "react-router-dom";
import ModificationDate from "./ModificationDate";

export default class Ranking extends Component {
    constructor(props) {
        super(props);
        this.state = {
            ranking: [],
            seasonId: props.seasonId,
            army: props.army,
            rankingLastModified: null,
            rankingTitle: "Ranking"
        };
    }

    componentDidMount() {
        Promise.resolve()
            .then(() => getRanking(this.state.seasonId, this.state.army))
            .then((rankingData) => this.setState({
                ranking: rankingData.ranking,
                rankingLastModified: rankingData.rankingLastModified,
                rankingTitle: rankingData.rankingTitle
            }));
    }

    componentDidUpdate(previousProps, previousState, snapshot) {
        const { seasonId, army } = previousProps;
        if (seasonId !== this.props.seasonId || army !== this.props.army) {
            const newSeason = seasonId === this.props.seasonId ? seasonId : this.props.seasonId;
            const newArmy  = army === this.props.army ? army : this.props.army;
            Promise.resolve()
                .then(() => getRanking(newSeason, newArmy))
                .then((rankingData) => this.setState({
                    seasonId: newSeason,
                    ranking: rankingData.ranking,
                    rankingLastModified: rankingData.rankingLastModified,
                    rankingTitle: rankingData.rankingTitle,
                    army: newArmy
                }));
        }
    }

    render() {
        let lp = 1;
        const { seasonId, ranking, rankingLastModified, rankingTitle } = this.state;

        return (
            <div>
                <h2>{rankingTitle}</h2>
                <ModificationDate lastModified={rankingLastModified} />
                <table className="table table-striped mt-4">
                    <thead className="thead-dark">
                    <tr>
                        <th>Lp.</th>
                        <th>ID</th>
                        <th>Imię</th>
                        <th>Nick</th>
                        <th>Klub</th>
                        <th>Miasto</th>
                        <th>Suma</th>
                        <th>Turnieje <span className="originalPoints">(zaliczone / zagrane)</span></th>
                        <th>Ind.</th>
                    </tr>
                    </thead>
                    <tbody>
                    {ranking.map(position => {
                        return (<tr key={position.id}>
                            <th scope="row">{lp++}</th>
                            <td>{position.player.legacyId}</td>
                            <td>{position.player.firstName}</td>
                            <td>{position.player.nickname}</td>
                            <td>{position.player.association}</td>
                            <td>{position.player.town}</td>
                            <td>{position.points}</td>
                            <td>{position.tournamentCount} / {position.tournamentsAttendedCount}</td>
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
