import React, { Component } from 'react';

import {createTournament} from "../services/tournaments";
import {Redirect} from "react-router-dom";

export default class AddTournament extends Component {
    constructor(props) {
        super(props);
        this.state = {
            tournamentData: {
                name: '',
                town: '',
                venue: '',
                points: 0,
                rulesUrl: '',
                date: '',
                organiser: '.',
                type: 'single',
                rank: 'L',
                playersInTeam: 3
            },
            hasError: false,
            tournamentId: 0
        };
    }

    addTournament(event) {
        event.preventDefault();

        return Promise.resolve()
            .then(() => this.setState({ hasError: false }))
            .then(() => createTournament(this.state.tournamentData))
            .then((tournamentData) => this.setState({ tournamentId: tournamentData.id }))
            .catch(() => this.setState({ hasError: true }));
    }

    handleChange(field, event) {
        const tournamentData = this.state.tournamentData;
        tournamentData[field] = event.target.value;
        this.setState({ tournamentData });
    }

    render() {
        const { hasError, tournamentData, tournamentId } = this.state;
        const redirectToList = tournamentId > 0;
        console.log(tournamentId);
        const playersInTeamClass = tournamentData.type === 'team' ? 'form-group visible' : 'form-group invisible';

        return (
            <div>
                {redirectToList && <Redirect push to={{
                    pathname: '/future-tournaments',
                    state: { newTournamentId: tournamentId }
                }} />}
                <h2>Dodaj turniej</h2>
                {hasError && <div className="alert alert-danger" role="alert">
                    Wypełnij poprawnie formularz i spróbuj jeszcze raz!
                </div>}
                <form>
                    <div className="form-group">
                        <label htmlFor="name">Nazwa</label>
                        <input type="text" className="form-control" id="name" placeholder="Nazwa turnieju" value={tournamentData.name} onChange={(e) => this.handleChange('name', e)} />
                    </div>
                    <div className="form-group">
                        <label htmlFor="town">Miejscowość</label>
                        <input type="text" className="form-control" id="town" placeholder="Miejscowość" value={tournamentData.town} onChange={(e) => this.handleChange('town', e)}/>
                    </div>
                    <div className="form-group">
                        <label htmlFor="venue">Miejsce</label>
                        <input type="text" className="form-control" id="venue" placeholder="Miejsce" value={tournamentData.venue} onChange={(e) => this.handleChange('venue', e)}/>
                    </div>
                    <div className="form-group">
                        <label htmlFor="points">Punkty</label>
                        <input type="number" className="form-control" id="points" placeholder="Punnkty" value={tournamentData.points} onChange={(e) => this.handleChange('points', e)} />
                    </div>
                    <div className="form-group">
                        <label htmlFor="rules">Adres strony / regulaminu</label>
                        <input type="text" className="form-control" id="rules" placeholder="Strona / regulamin" value={tournamentData.rulesUrl} onChange={(e) => this.handleChange('rulesUrl', e)} />
                    </div>
                    <div className="form-group">
                        <label htmlFor="date">Data</label>
                        <input type="text" className="form-control" id="date" placeholder="Data" value={tournamentData.date} onChange={(e) => this.handleChange('date', e)} />
                    </div>
                    <div className="form-group">
                        <label htmlFor="organiser">Organizator</label>
                        <input type="text" className="form-control" id="organiser" placeholder="Organizator" value={tournamentData.organiser} onChange={(e) => this.handleChange('organiser', e)} />
                    </div>

                    <p>Rodzaj turnieju</p>
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="type" id="type_single" value="single" checked={tournamentData.type === 'single' } onChange={(e) => this.handleChange('type', e)} />
                        <label className="form-check-label" htmlFor="type_single">Singiel</label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="type" id="type_double" value="double" checked={tournamentData.type === 'double' } onChange={(e) => this.handleChange('type', e)}/>
                        <label className="form-check-label" htmlFor="type_double">Parówka</label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="type" id="type_team" value="team" checked={tournamentData.type === 'team' } onChange={(e) => this.handleChange('type', e)} />
                        <label className="form-check-label" htmlFor="type_team">Drużynowy</label>
                    </div>

                    <p>Ranga</p>
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="rank" id="rank_local" value="local" checked={tournamentData.rank === 'local' } onChange={(e) => this.handleChange('rank', e)} />
                        <label className="form-check-label" htmlFor="type_local">Lokalny</label>
                    </div>
                    <div className="form-check">
                        <input className="form-check-input" type="radio" name="rank" id="rank_master" value="master" checked={tournamentData.rank === 'master' } onChange={(e) => this.handleChange('rank', e)} />
                        <label className="form-check-label" htmlFor="rank_master">Master</label>
                    </div>

                    <div className={playersInTeamClass} >
                        <label htmlFor="playersInTeam">Liczba graczy w drużynie</label>
                        <input type="number" className="form-control" id="playersInTeam" placeholder="Graczy w drużynie" value={tournamentData.playersInTeam} onChange={(e) => this.handleChange('playersInTeam', e)} />
                    </div>

                    <button type="submit" className="btn btn-primary" onClick={(event) => this.addTournament(event)}>Dodaj</button>
                </form>
            </div>);
    }
}
