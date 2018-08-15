import React, { Component } from 'react';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import Home from './Home';
import Navbar from './Navbar';
import Players from './Players';
import PreviousTournaments from "./PreviousTournaments";
import FutureTournaments from "./FutureTournaments";
import Ranking from "./Ranking";
import AddTournament from "./AddTournament";
import Individual from "./Individual";

export default class Main extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Navbar />
                    <Switch>
                        <Route exact path="/" component={Home}/>
                        <Route exact path="/players" component={Players} />
                        <Route exact path="/previous-tournaments" component={PreviousTournaments} />
                        <Route exact path="/future-tournaments" component={FutureTournaments} />
                        <Route exact path="/ranking" component={Ranking} />
                        <Route path="/ranking/individual/:playerId" component={Individual} />
                        <Route exact path="/add-tournament" component={AddTournament} />
                    </Switch>
                </div>
            </Router>);
        }
}
