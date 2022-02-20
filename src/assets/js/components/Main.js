import React, { Component } from 'react';
import {BrowserRouter as Router, Route, Routes } from 'react-router-dom';

import Navbar from './Navbar';
import * as RankingRoutes from "../routes";

export default class Main extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Navbar />
                    <Routes>
                        <Route exact path="/" element={<RankingRoutes.RankingRoute />} />
                        <Route exact path="/home" element={<RankingRoutes.HomeRoute />}/>
                        <Route exact path="/players" element={<RankingRoutes.PlayersRoute />} />
                        <Route exact path="/previous-tournaments" element={<RankingRoutes.PreviousTournamentsRoute />} />
                        <Route exact path="/future-tournaments" element={<RankingRoutes.FutureTournamentsRoute />} />
                        <Route exact path="/ranking" element={<RankingRoutes.RankingRoute />} />
                        <Route path="/army-ranking/:seasonId/:army" element={<RankingRoutes.RankingRoute />} />
                        <Route path="/army-ranking/:army" element={<RankingRoutes.RankingRoute />} />
                        <Route path="/ranking/:seasonId/individual/:playerId" element={<RankingRoutes.IndividualRoute />} />
                        <Route path="/ranking/individual/:playerId" element={<RankingRoutes.IndividualRoute />} />
                        <Route path="/ranking/:seasonId" element={<RankingRoutes.RankingRoute />} />
                        <Route exact path="/add-tournament" element={<RankingRoutes.AddTournamentRoute />} />
                        <Route path="/tournament/:tournamentId" element={<RankingRoutes.TournamentResultsRoute />} />
                        <Route path="/archive-seasons" element={<RankingRoutes.ArchiveSeasonsRoute />} />
                    </Routes>
                </div>
            </Router>);
        }
}
