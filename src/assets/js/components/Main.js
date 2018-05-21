import React, { Component } from 'react';
import {BrowserRouter as Router, Route, Switch} from 'react-router-dom';

import Home from './Home';
import Navbar from './Navbar';
import Players from './Players';

export default class Main extends Component {
    render() {
        return (
            <Router>
                <div>
                    <Navbar />
                    <Switch>
                        <Route exact path="/" component={Home} />
                        <Route exact path="/players" component={Players} />
                    </Switch>
                </div>
            </Router>);
        }
}
