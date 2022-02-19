import React, { Component } from 'react';

import {Link} from "react-router-dom";
import shortId from "shortid";
import {getArchiveSeasons} from "../services/seasons";

export default class ArchiveSeasons extends Component {
    constructor(props) {
        super(props);
        this.state = {
            seasons: []
        };
    }

    componentDidMount() {
        if (this.state.seasons.length === 0) {
            Promise.resolve()
                .then(() => getArchiveSeasons())
                .then((seasons) => this.setState({ seasons: seasons.seasons }));
        }
    }

    render() {
        return (<>
            <h1>Rankingi z poprzednich sezonów</h1>
            {this.state.seasons.map(season => {
                return (<div key={shortId()} className="">
                    <h2>Sezon {season.name}</h2>
                    <div className="">
                        <Link to={`/ranking/${season.seasonId}`} className="nav-link text-dark">Ranking Generalny</Link>
                        <div className="dropdown-divider" />
                        <Link to={`/army-ranking/${season.seasonId}/BH`} className="nav-link text-dark army-nav-link">Beast Herds</Link>
                        <Link to={`/army-ranking/${season.seasonId}/DL`} className="nav-link text-dark army-nav-link">Daemon Legions</Link>
                        <Link to={`/army-ranking/${season.seasonId}/DE`} className="nav-link text-dark army-nav-link">Dread Elves</Link>
                        <Link to={`/army-ranking/${season.seasonId}/DH`} className="nav-link text-dark army-nav-link">Dwarven Holds</Link>
                        <Link to={`/army-ranking/${season.seasonId}/EOS`} className="nav-link text-dark army-nav-link">Empire of Sonnstahl</Link>
                        <Link to={`/army-ranking/${season.seasonId}/HE`} className="nav-link text-dark army-nav-link">Highborn Elves</Link>
                        <Link to={`/army-ranking/${season.seasonId}/ID`} className="nav-link text-dark army-nav-link">Infernal Dwarves</Link>
                        <Link to={`/army-ranking/${season.seasonId}/KOE`} className="nav-link text-dark army-nav-link">Kingdom of Equitaine</Link>
                        <Link to={`/army-ranking/${season.seasonId}/OK`} className="nav-link text-dark army-nav-link">Ogre Khans</Link>
                        <Link to={`/army-ranking/${season.seasonId}/OG`} className="nav-link text-dark army-nav-link">Orcs and Goblins</Link>
                        <Link to={`/army-ranking/${season.seasonId}/SA`} className="nav-link text-dark army-nav-link">Saurian Ancients</Link>
                        <Link to={`/army-ranking/${season.seasonId}/SE`} className="nav-link text-dark army-nav-link">Sylvan Elves</Link>
                        <Link to={`/army-ranking/${season.seasonId}/VS`} className="nav-link text-dark army-nav-link">The Vermin Swarm</Link>
                        <Link to={`/army-ranking/${season.seasonId}/UD`} className="nav-link text-dark army-nav-link">Undying Dynasties</Link>
                        <Link to={`/army-ranking/${season.seasonId}/VC`} className="nav-link text-dark army-nav-link">Vampire Covenant</Link>
                        <Link to={`/army-ranking/${season.seasonId}/WDG`} className="nav-link text-dark army-nav-link">Warriors of the Dark Gods</Link>
                    </div>
                    <div className="clear-float" />
                    <div className="archive-divider" />
                </div>)
            })}
            </>);
    }
}
