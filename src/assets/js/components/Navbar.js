import React, { Component } from 'react';
import { Link } from 'react-router-dom';

export default class Navbar extends Component {

    render() {
        const { path } = this.props;
        const homeActive = path === '/' ? 'active' : '';
        const homeClasses = 'nav-item ' + homeActive;
        const playersActive = path === '/players' ? 'active' : '';
        const playersClasses = 'nav-item ' + playersActive;

        return (<div className="navbar navbar-expand-sm  navbar-dark bg-dark">
            <div className="collapse navbar-collapse" id="navbarSupportedContent">
                <ul className="navbar-nav mr-auto">
                    <li className={homeClasses}>
                        <Link
                            to="/"
                            className="nav-link">Home
                        </Link>
                    </li>
                    <li className={playersClasses}>
                        <Link
                            to="/players"
                            className="nav-link">Gracze
                        </Link>
                    </li>
                    <li className="nav-item">
                        <Link
                            to="/future-tournaments"
                            className="nav-link">Nadchodzące turnieje
                        </Link>
                    </li>
                    <li className="nav-item">
                        <Link
                            to="/previous-tournaments"
                            className="nav-link">Odbyte turnieje
                        </Link>
                    </li>
                    <li className="nav-item">
                        <Link
                            to="/ranking"
                            className="nav-link">Ranking
                        </Link>
                    </li>
                </ul>
            </div>
        </div>);
    }
}
