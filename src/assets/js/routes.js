import React from "react";
import {useParams} from "react-router-dom";
import Ranking from "./components/Ranking";
import Home from "./components/Home";
import PreviousTournaments from "./components/PreviousTournaments";
import FutureTournaments from "./components/FutureTournaments";
import Individual from "./components/Individual";
import AddTournament from "./components/AddTournament";
import TournamentResults from "./components/TournamentResults";
import ArchiveSeasons from "./components/ArchiveSeasons";
import Players from "./components/Players";

const RankingRoute = () => {
    const params = useParams()
    return (<Ranking seasonId={params.seasonId} army={params.army} />);
}

const HomeRoute = () => (<Home />)
const PlayersRoute = () => (<Players />)
const PreviousTournamentsRoute = () => (<PreviousTournaments />)
const FutureTournamentsRoute = () => (<FutureTournaments />)
const IndividualRoute = () => {
    const params = useParams()
    return (<Individual playerId={params.playerId} seasonId={params.seasonId} />)
}
const AddTournamentRoute = () => (<AddTournament />)
const TournamentResultsRoute = () => {
    const params = useParams()
    return (<TournamentResults tournamentId={params.tournamentId} />)
}
const ArchiveSeasonsRoute = () => (<ArchiveSeasons />)
export { RankingRoute, HomeRoute, PlayersRoute, PreviousTournamentsRoute, FutureTournamentsRoute, IndividualRoute, AddTournamentRoute, TournamentResultsRoute, ArchiveSeasonsRoute }
