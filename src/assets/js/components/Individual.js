import React, { Component } from 'react';

import { getIndividualRanking } from '../services/ranking';
import { ranks, tournamentTypes } from '../const/tournaments';

export default class Individual extends Component {
    constructor(props) {
        super(props);
        this.state = {
            result: {
                player: null,
                tournaments: {},
                points: null
            },
            playerId: props.match.params.playerId
        };
    }

    componentDidMount() {
        if (this.state.result.player === null) {
            Promise.resolve()
                .then(() => getIndividualRanking(this.state.playerId))
                .then((result) => this.setState({ result }));
        }
    }

    render() {
        const { player, tournaments, points } = this.state.result;
        const showRanking = player !== null;
        return (<div>
            {showRanking && <div>
                <h2>Wyniki gracza {player.firstName} {player.nickname}</h2>
                <h4>Suma punktów w lidze: {points}</h4>
                <table className="table mt-4">
                    <thead className="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Nazwa</th>
                            <th>Data</th>
                            <th>Ranga / typ</th>
                            <th>Miejsce</th>
                            <th>Punkty</th>
                            <th>Armia</th>
                        </tr>
                    </thead>
                    <tbody>
                    {tournaments.map(tournament => {
                        const includedClass = tournament.tournamentPointsIncluded ? "tournament-included" : "";
                        const judgeClass = tournament.playerWasAJudge ? (tournament.tournamentPointsIncluded ? "judge-included" : "judge-not-included") : "";

                        const rankType = ranks[tournament.tournamentRank] + " " + tournamentTypes[tournament.tournamentType];
                        const playersInTeam = tournament.tournamentType === 'team' ? `(${tournament.tournamentPlayersInTeam})` : '';

                        const originalPoints = tournament.tournamentPointsIncluded && tournament.playersPoints !== tournament.originalPoints ? ' (' + tournament.originalPoints + ')' : '';

                        return <tr className={`${includedClass} ${judgeClass}`} key={tournament.id}>
                            <td>{tournament.legacyId}</td>
                            <td>{tournament.tournamentName}</td>
                            <td>{tournament.tournamentDate}</td>
                            <td>{rankType} {playersInTeam}</td>
                            <td className="centered">{tournament.playersPlace}</td>
                            <td className="centered " >{tournament.playersPoints}<span className="originalPoints">{originalPoints}</span></td>
                            <td className="centered">{tournament.playersArmy}</td>
                        </tr>
                    })}
                    </tbody>
                </table>
            </div>}
            {!showRanking && <div className="loader">Moment...</div>}
                <div>
                    <p>Legenda:</p>
                    <div className="legend tournament-included">Turniej zaliczony do rankingu</div>
                    <div className="legend judge-included">Premia sędziowska, zaliczona do rankingu</div>
                    <div className="legend judge-not-included">Premia sędziowska, niezaliczona do rankingu</div>
                    <div className="legend master-points-as-local">W przypadku mastera zaliczonego
                        jako lokal punkty w&nbsp;nawiasie pokazują ile rzeczywiście zostało doliczonych do rankingu
                        , np. 240 <span className="originalPoints">(80)</span></div>
                </div>
            </div>
        );
    }
}
