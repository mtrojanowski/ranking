import React, { Component } from 'react';
import { Link } from 'react-router-dom';

export default class Navbar extends Component {

    render() {
        const { path } = this.props;
        const homeActive = path === '/' ? 'active' : '';
        const homeClasses = 'nav-item ' + homeActive;
        const playersActive = path === '/players' ? 'active' : '';
        const playersClasses = 'nav-item ' + playersActive;

        return (<div className="menu navbar navbar-expand-sm  navbar-dark bg-dark">
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
                    <li className="dropdown nav-item">
                        <a href="" id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" className="nav-link">
                            Rankingi armijne
                        </a>
                        <div className="dropdown-menu nav-item" aria-labelledby="dLabel">
                            <Link to="/army-ranking/BH" className="nav-link text-dark">Beast Herds</Link>
                            <Link to="/army-ranking/DL" className="nav-link text-dark">Daemon Legions</Link>
                            <Link to="/army-ranking/DE" className="nav-link text-dark">Dread Elves</Link>
                            <Link to="/army-ranking/DH" className="nav-link text-dark">Dwarven Holds</Link>
                            <Link to="/army-ranking/EOS" className="nav-link text-dark">Empire of Sonnstahl</Link>
                            <Link to="/army-ranking/HE" className="nav-link text-dark">Highborn Elves</Link>
                            <Link to="/army-ranking/ID" className="nav-link text-dark">Infernal Dwarves</Link>
                            <Link to="/army-ranking/KOE" className="nav-link text-dark">Kingdom of Equitaine</Link>
                            <Link to="/army-ranking/OK" className="nav-link text-dark">Ogre Khans</Link>
                            <Link to="/army-ranking/OG" className="nav-link text-dark">Orcs and Goblins</Link>
                            <Link to="/army-ranking/SA" className="nav-link text-dark">Saurian Ancients</Link>
                            <Link to="/army-ranking/SE" className="nav-link text-dark">Sylvan Elves</Link>
                            <Link to="/army-ranking/VS" className="nav-link text-dark">The Vermin Swarm</Link>
                            <Link to="/army-ranking/UD" className="nav-link text-dark">Undying Dynasties</Link>
                            <Link to="/army-ranking/VC" className="nav-link text-dark">Vampire Covenant</Link>
                            <Link to="/army-ranking/WDG" className="nav-link text-dark">Warriors of the Dark Gods</Link>
                        </div>
                    </li>
                    <li className="dropdown nav-item">
                        <a href="" id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" className="nav-link">
                            Rankingi archiwalne
                        </a>
                        <div className="dropdown-menu nav-item" aria-labelledby="dLabel">
                            <p className="nav-season-separator">Sezon 2018</p>
                            <Link to="/ranking/5b0d7c33fd0f9b6a4991169b" className="nav-link text-dark">Generalny</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/BH" className="nav-link text-dark">Beast Herds</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/DL" className="nav-link text-dark">Daemon Legions</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/DE" className="nav-link text-dark">Dread Elves</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/DH" className="nav-link text-dark">Dwarven Holds</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/EOS" className="nav-link text-dark">Empire of Sonnstahl</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/HE" className="nav-link text-dark">Highborn Elves</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/ID" className="nav-link text-dark">Infernal Dwarves</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/KOE" className="nav-link text-dark">Kingdom of Equitaine</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/OK" className="nav-link text-dark">Ogre Khans</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/OG" className="nav-link text-dark">Orcs and Goblins</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/SA" className="nav-link text-dark">Saurian Ancients</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/SE" className="nav-link text-dark">Sylvan Elves</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/VS" className="nav-link text-dark">The Vermin Swarm</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/UD" className="nav-link text-dark">Undying Dynasties</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/VC" className="nav-link text-dark">Vampire Covenant</Link>
                            <Link to="/army-ranking/5b0d7c33fd0f9b6a4991169b/WDG" className="nav-link text-dark">Warriors of the Dark Gods</Link>
                        </div>
                    </li>

                    <li className="nav-item">
                        <Link
                            to="/add-tournament"
                            className="nav-link">Dodaj turniej
                        </Link>
                    </li>
                </ul>
            </div>
        </div>);
    }
}
