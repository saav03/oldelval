const indice_contain = document.getElementById('indice_contain');

const i_question_modal = document.querySelectorAll('.i-question_modal');
let txt = '';

for (let i = 0; i < i_question_modal.length; i++) {
    // console.log(i_question_modal[i]);
    let id_indice_kpi = i_question_modal[i].getAttribute('data-id');
    i_question_modal[i].addEventListener('click', () => {
        txt = '';
        switch (id_indice_kpi) {
            case '12':
                txt = `
                    <p id="math-formula-IC">
                        <math alttext="{\text 'IC' = ({∑\text 'HH de capacitacion'\ }/ ∑\text 'HH Trabajadas') × 100" display="block" class="ma-block">
                            <mrow>
                                <mtext>II AP</mtext>
                                <mo>=</mo>
                                <mrow>
                                    <mrow>
                                        <mo>(</mo>
                                        <mfrac>
                                            <mrow class="ma-repel-adj">
                                                <mrow>
                                                    <mtext>N° trabajadores accidentados x 1000</mtext>
                                                    <mi mathvariant="normal" class="ma-upright"> </mi>
                                                </mrow>
                                            </mrow>
                                            <mrow class="ma-repel-adj">
                                                <mtext>N° trabajadores expuestos</mtext>
                                            </mrow>
                                        </mfrac>
                                        <mo>)</mo>
                                    </mrow>
                                </mrow>
                            </mrow>
                        </math>
                    </p>
                `;
                indice_contain.innerHTML = txt;
                break;
            case '13':
                txt = `
                    <p id="math-formula-IC">
                        <math alttext="{\text 'IC' = ({∑\text 'HH de capacitacion'\ }/ ∑\text 'HH Trabajadas') × 100" display="block" class="ma-block">
                            <mrow>
                                <mtext>IF AP CD</mtext>
                                <mo>=</mo>
                                <mrow>
                                    <mrow>
                                        <mo>(</mo>
                                        <mfrac>
                                            <mrow class="ma-repel-adj">
                                                <mrow>
                                                    <mtext>N°Accidentes con dias perdidos</mtext>
                                                    <mi mathvariant="normal" class="ma-upright"> </mi>
                                                </mrow>
                                            </mrow>
                                            <mrow class="ma-repel-adj">
                                                <mtext>Horas hombre trabajadas</mtext>
                                            </mrow>
                                        </mfrac>
                                        <mo>)</mo>
                                    </mrow>
                                    <mo lspace=".22em" rspace=".22em">×</mo>
                                    <mn>1000000</mn>
                                </mrow>
                            </mrow>
                        </math>
                    </p>
                `;
                indice_contain.innerHTML = txt;
                break;
            case '14':
                txt = `
                    <p id="math-formula-IC">
                        <math alttext="{\text 'IC' = ({∑\text 'HH de capacitacion'\ }/ ∑\text 'HH Trabajadas') × 100" display="block" class="ma-block">
                            <mrow>
                                <mtext>IF AAP</mtext>
                                <mo>=</mo>
                                <mrow>
                                    <mrow>
                                        <mo>(</mo>
                                        <mfrac>
                                            <mrow class="ma-repel-adj">
                                                <mrow>
                                                    <mtext>N°Accidente operativo de alto potencial x 1000000</mtext>
                                                    <mi mathvariant="normal" class="ma-upright"> </mi>
                                                </mrow>
                                            </mrow>
                                            <mrow class="ma-repel-adj">
                                                <mtext>Horas trabajadas</mtext>
                                            </mrow>
                                        </mfrac>
                                        <mo>)</mo>
                                    </mrow>
                                </mrow>
                            </mrow>
                        </math>
                    </p>
                `;
                indice_contain.innerHTML = txt;
                break;
            case '15':
                txt = `
                    <p id="math-formula-IC">
                        <math alttext="{\text 'IC' = ({∑\text 'HH de capacitacion'\ }/ ∑\text 'HH Trabajadas') × 100" display="block" class="ma-block">
                            <mrow>
                                <mtext>IF AP SD</mtext>
                                <mo>=</mo>
                                <mrow>
                                    <mrow>
                                        <mo>(</mo>
                                        <mfrac>
                                            <mrow class="ma-repel-adj">
                                                <mrow>
                                                    <mtext>N°Trabajdores con acci.operativos sin dias perdidos x 1000000</mtext>
                                                    <mi mathvariant="normal" class="ma-upright"> </mi>
                                                </mrow>
                                            </mrow>
                                            <mrow class="ma-repel-adj">
                                                <mtext>Horas trabajadas</mtext>
                                            </mrow>
                                        </mfrac>
                                        <mo>)</mo>
                                    </mrow>
                                </mrow>
                            </mrow>
                        </math>
                    </p>
                `;
                indice_contain.innerHTML = txt;
                break;
            case '16':
                txt = `
                    <p id="math-formula-IC">
                        <math alttext="{\text 'IC' = ({∑\text 'HH de capacitacion'\ }/ ∑\text 'HH Trabajadas') × 100" display="block" class="ma-block">
                            <mrow>
                                <mtext>IG AP</mtext>
                                <mo>=</mo>
                                <mrow>
                                    <mrow>
                                        <mo>(</mo>
                                        <mfrac>
                                            <mrow class="ma-repel-adj">
                                                <mrow>
                                                    <mtext>N° Cantidad de dias perdidos x 1000000</mtext>
                                                    <mi mathvariant="normal" class="ma-upright"> </mi>
                                                </mrow>
                                            </mrow>
                                            <mrow class="ma-repel-adj">
                                                <mtext>Horas trabajadas</mtext>
                                            </mrow>
                                        </mfrac>
                                        <mo>)</mo>
                                    </mrow>
                                </mrow>
                            </mrow>
                        </math>
                    </p>
                `;
                indice_contain.innerHTML = txt;
                break;
            case '23':
                txt = `
                    <p id="math-formula-IC">
                        <math alttext="{\text 'IC' = ({∑\text 'HH de capacitacion'\ }/ ∑\text 'HH Trabajadas') × 100" display="block" class="ma-block">
                            <mrow>
                                <mtext>IFAV</mtext>
                                <mo>=</mo>
                                <mrow>
                                    <mrow>
                                        <mo>(</mo>
                                        <mfrac>
                                            <mrow class="ma-repel-adj">
                                                <mrow>
                                                    <mtext>N° Cantidad de accidentes vehiculares x 1000000</mtext>
                                                    <mi mathvariant="normal" class="ma-upright"> </mi>
                                                </mrow>
                                            </mrow>
                                            <mrow class="ma-repel-adj">
                                                <mtext>∑ Kilómetros Recorridos</mtext>
                                            </mrow>
                                        </mfrac>
                                        <mo>)</mo>
                                    </mrow>
                                </mrow>
                            </mrow>
                        </math>
                    </p>
                `;
                indice_contain.innerHTML = txt;
                break;

            case '34':
                txt = `
                    <p id="math-formula-IC">
                        <math alttext="{\text 'IC' = ({∑\text 'HH de capacitacion'\ }/ ∑\text 'HH Trabajadas') × 100" display="block" class="ma-block">
                            <mrow>
                                <mtext>IC</mtext>
                                <mo>=</mo>
                                <mrow>
                                    <mrow>
                                        <mo>(</mo>
                                        <mfrac>
                                            <mrow class="ma-repel-adj">
                                                <mo>∑</mo>
                                                <mrow>
                                                    <mtext>HH de capacitacion</mtext>
                                                    <mi mathvariant="normal" class="ma-upright"> </mi>
                                                </mrow>
                                            </mrow>
                                            <mrow class="ma-repel-adj">
                                                <mo>∑</mo>
                                                <mtext>HH Trabajadas</mtext>
                                            </mrow>
                                        </mfrac>
                                        <mo>)</mo>
                                    </mrow>
                                    <mo lspace=".22em" rspace=".22em">×</mo>
                                    <mn>100</mn>
                                </mrow>
                            </mrow>
                        </math>
                    </p>
                `;
                indice_contain.innerHTML = txt;
                break;
            case '35':
                txt = `
                    <p id="math-formula-IC">
                        <math alttext="{\text 'IC' = ({∑\text 'HH de capacitacion'\ }/ ∑\text 'HH Trabajadas') × 100" display="block" class="ma-block">
                            <mrow>
                                <mtext>hs objetivo</mtext>
                                <mo>=</mo>
                                <mrow>
                                    <mrow>
                                        <mo>(</mo>
                                        <mfrac>
                                            <mrow class="ma-repel-adj">
                                                <mo>∑</mo>
                                                <mrow>
                                                    <mtext>HH de capacitacion * 0.01</mtext>
                                                    <mi mathvariant="normal" class="ma-upright"> </mi>
                                                </mrow>
                                            </mrow>
                                        </mfrac>
                                        <mo>)</mo>
                                    </mrow>
                                </mrow>
                            </mrow>
                        </math>
                    </p>
                `;
                indice_contain.innerHTML = txt;
                break;
        }
        // console.log(i_question_modal[i].getAttribute('data-id'));
    })

}