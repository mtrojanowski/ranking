import React, { Component } from 'react';

export default class Home extends Component {
    render() {
        return (<div>
            <div className="row">
                <div className="large-12 columns">
                    <h1>Polska Liga The 9<sup>th</sup> Age</h1>
                    <p>
                        Witamy na stronie Polskiej Ligi The 9<sup>th</sup> Age.
                    </p>
                    <h3>Oosoby odpowiedzialne:</h3>
                    <ul>
                        <li>Skarbnik:	<a href="http://forum.wfb-pol.org/memberlist.php?mode=viewprofile&u=2579" target="_blank">Laik</a></li>
                        <li>Koordynator ds. ogólnych:	<a href="http://forum.wfb-pol.org/memberlist.php?mode=viewprofile&u=2579" target="_blank">Laik</a></li>
                        <li>Koordynator ds. rankingu:	<a href="http://forum.wfb-pol.org/memberlist.php?mode=viewprofile&u=746" target="_blank">Guldur</a></li>
                        <li>Koordynator ds. strony rankingowej:	<a href="" target="_blank">-</a></li>
                        <li>Moderator:	<a href="http://forum.wfb-pol.org/memberlist.php?mode=viewprofile&u=2579" target="_blank">Laik</a></li>
                        <li>Wsparcie techniczne rankingu: <a href="https://forum.wfb-pol.org/memberlist.php?mode=viewprofile&u=749" target="_blank">Hrabia</a></li>
                    </ul>

                    <h3>Jak zgłosić nową osobę do Ligi</h3>
                    <p>
                        Napisz PW do Hrabiego na forum z imieniem, nazwiskiem, ksywą, miastem i klubem gracza.
                        Dostaniesz w odpowiedzi ID nowego gracza.
                    </p>
                    <p>W przyszłości planujemy przejść na bardziej zautomatyzowane zapisywanie graczy do ligi :)</p>

                    <h3>Zgłaszanie wyników</h3>
                    <p>
                        Wyniki turniejów należy przesyłać na adres e-mail wynikiligi( at )gmail.com w formacie tekstowym,
                        podając: miejsce gracza, jego ID, armię którą grał. Przesyłając wyniki podaj także ID turnieju,
                        oraz wskaż kto powinien dostać punkty za sędziowanie.
                    </p>
                    <p>
                        W sprawie wyników, zmiany w turniejach, rankingu, edycja graczy prosimy o kontakt na adres
                        e-mail wynikiligi( at )gmail.com [ ( at ) = @]
                    </p>
                </div>
            </div>
        </div>);
    }
}
