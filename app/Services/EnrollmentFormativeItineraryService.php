<?php

namespace App\Services;

use App\Models\LegacyEnrollment;
use App\Rules\RequiredEnrollmentItineraryComposition;
use App\Rules\RequiredEnrollmentItineraryCourse;
use App\Rules\RequiredEnrollmentConcomitantItinerary;
use iEducar\Modules\ValueObjects\EnrollmentFormativeItineraryValueObject;

class EnrollmentFormativeItineraryService
{
    public function saveFormativeItinerary(
        LegacyEnrollment $enrollment,
        EnrollmentFormativeItineraryValueObject $itineraryData
    )
    {
        $this->validate($itineraryData);
        $enrollment->tipo_itinerario = $this->convertArrayToDBField($itineraryData->itineraryType);
        $enrollment->composicao_itinerario = $this->convertArrayToDBField($itineraryData->itineraryComposition);
        $enrollment->curso_itinerario = $itineraryData->itineraryCourse;
        $enrollment->itinerario_concomitante = $itineraryData->concomitantItinerary;

        $enrollment->save();
    }

    private function convertArrayToDBField($field)
    {
        if (is_array($field)) {
            return '{' . implode(',', $field) . '}';
        }

        return null;
    }

    private function validate(EnrollmentFormativeItineraryValueObject $itineraryData)
    {
        validator(
            [
                'formative_itinerary' => $itineraryData,
                'formative_itinerary_composition' => $itineraryData->itineraryComposition,
            ],
            [
                'formative_itinerary' => [
                    new RequiredEnrollmentItineraryComposition(),
                    new RequiredEnrollmentItineraryCourse(),
                    new RequiredEnrollmentConcomitantItinerary(),
                ],
                'formative_itinerary_composition' => [
                    'max:4',
                ]
            ],
            [
                'formative_itinerary_composition.max' => 'O campo <b>Composição do itinerário formativo integrado<b> não pode ter mais de 4 opções selecionadas.'
            ],
        )->validate();
    }
}
