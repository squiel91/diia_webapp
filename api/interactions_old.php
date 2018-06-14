	<?php

    include_once('debug_config.php');

    include_once('cors_config.php');
    header('Content-Type: application/json; charset=utf-8;');

?>
{
	"courses": [
		{
			"id_curso": "1",
			"titulo": "Associate Degree in Education"
		}
	],
	"nodes": [  
		{
			"id": 1,
			"name": "Andrés",
			"courses": ["1", "2"],
			"type": "docente"
		},
		{
			"id": 2,
			"name": "Arturo",
			"courses": ["1"],
			"type": "alumno"
		},
		{
			"id": 3,
			"name": "Dolores",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 4,
			"name": "Luis Miguel",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 5,
			"name": "Alfredo",
			"courses": ["1"],
			"type": "alumno"
		},
		{
			"id": 6,
			"name": "Teodoro",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 7,
			"name": "Marta",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 8,
			"name": "Elsa",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 9,
			"name": "Samuel",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 10,
			"name": "Federico",
			"courses": ["1"],
			"type": "alumno"
		},
		{
			"id": 11,
			"name": "Adela",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 12,
			"name": "Marisol",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 13,
			"name": "Elena",
			"courses": ["1"],
			"type": "alumno"
		},
		{
			"id": 14,
			"name": "Guadalupe",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 15,
			"name": "Beatriz",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 16,
			"name": "Armando",
			"courses": ["2"],
			"type": "alumno"
		},
		{
			"id": 17,
			"name": "Octavio",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 18,
			"name": "Diana",
			"courses": ["2"],
			"type": "alumno"
		},
		{
			"id": 19,
			"name": "José María",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 20,
			"name": "María Luisa",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 21,
			"name": "Barbara",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 22,
			"name": "María Eugenia",
			"courses": ["2"],
			"type": "alumno"
		},
		{
			"id": 23,
			"name": "Julia",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 26,
			"name": "Guillermina",
			"courses": ["1", "2"],
			"type": "alumno"
		},
		{
			"id": 25,
			"name": "Adrina",
			"courses": ["2"],
			"type": "alumno"
		},
		{
			"id": 24,
			"name": "Link de Celulas",
			"courses": ["1", "2"],
			"type": "material"
		}
	],
	"interactions": [
		{
			"id": "1_9",
			"origin": 1,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 9,
			"date": "2018-02-05"
		},
		{
			"id": "1_2",
			"origin": 1,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 2,
			"date": "2018-02-05"
		},
		{
			"id": "14_5",
			"origin": 14,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 5,
			"date": "2018-02-05"
		},
		{
			"id": "19_15",
			"origin": 19,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 15,
			"date": "2018-02-05"
		},
		{
			"id": "7_22",
			"origin": 7,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 22,
			"date": "2018-02-05"
		},
		{
			"id": "4_3",
			"origin": 4,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 3,
			"date": "2018-02-05"
		},
		{
			"id": "6_11",
			"origin": 6,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 11,
			"date": "2018-02-05"
		},
		{
			"id": "10_19",
			"origin": 10,
			"type": "visualizacion",
			"value": 11,
			"course": "2",
			"target": 19,
			"date": "2018-02-05"
		},
		{
			"id": "20_12",
			"origin": 20,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 12,
			"date": "2018-02-05"
		},
		{
			"id": "3_7",
			"origin": 3,
			"type": "visualizacion",
			"value": 11,
			"course": "2",
			"target": 7,
			"date": "2018-02-05"
		},
		{
			"id": "12_6",
			"origin": 12,
			"type": "visualizacion",
			"value": 11,
			"course": "2",
			"target": 6,
			"date": "2018-02-05"
		},
		{
			"id": "7_20",
			"origin": 7,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 20,
			"date": "2018-02-05"
		},
		{
			"id": "4_7",
			"origin": 4,
			"type": "visualizacion",
			"value": 11,
			"course": "2",
			"target": 7,
			"date": "2018-02-05"
		},
		{
			"id": "23_8",
			"origin": 23,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 8,
			"date": "2018-02-05"
		},
		{
			"id": "2_22",
			"origin": 2,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 22,
			"date": "2018-02-05"
		},
		{
			"id": "17_23",
			"origin": 17,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 23,
			"date": "2018-02-05"
		},
		{
			"id": "4_24",
			"origin": 4,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 24,
			"date": "2018-02-05"
		},
		{
			"id": "21_3",
			"origin": 21,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 3,
			"date": "2018-02-05"
		},
		{
			"id": "21_4",
			"origin": 21,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 4,
			"date": "2018-02-05"
		},
		{
			"id": "15_16",
			"origin": 15,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 16,
			"date": "2018-02-05"
		},
		{
			"id": "22_18",
			"origin": 22,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 18,
			"date": "2018-02-05"
		},
		{
			"id": "18_11",
			"origin": 18,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 11,
			"date": "2018-02-05"
		},
		{
			"id": "10_1",
			"origin": 10,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 1,
			"date": "2018-02-01"
		},
		{
			"id": "3_13",
			"origin": 3,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 13,
			"date": "2018-02-01"
		},
		{
			"id": "20_17",
			"origin": 20,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 17,
			"date": "2018-02-01"
		},
		{
			"id": "5_12",
			"origin": 5,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 12,
			"date": "2018-02-01"
		},
		{
			"id": "19_18",
			"origin": 19,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 18,
			"date": "2018-02-01"
		},
		{
			"id": "23_16",
			"origin": 23,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 16,
			"date": "2018-02-01"
		},
		{
			"id": "3_8",
			"origin": 3,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 8,
			"date": "2018-02-01"
		},
		{
			"id": "22_16",
			"origin": 22,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 16,
			"date": "2018-02-01"
		},
		{
			"id": "3_15",
			"origin": 3,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 15,
			"date": "2018-02-01"
		},
		{
			"id": "22_7",
			"origin": 22,
			"type": "visualizacion",
			"value": 11,
			"course": "2",
			"target": 7,
			"date": "2018-02-01"
		},
		{
			"id": "6_12",
			"origin": 6,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 12,
			"date": "2018-02-01"
		},
		{
			"id": "14_20",
			"origin": 14,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 20,
			"date": "2018-02-01"
		},
		{
			"id": "12_7",
			"origin": 12,
			"type": "visualizacion",
			"value": 11,
			"course": "2",
			"target": 7,
			"date": "2018-02-01"
		},
		{
			"id": "12_2",
			"origin": 12,
			"type": "visualizacion",
			"value": 11,
			"course": "2",
			"target": 2,
			"date": "2018-02-01"
		},
		{
			"id": "1_8",
			"origin": 1,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 8,
			"date": "2018-02-01"
		},
		{
			"id": "4_1",
			"origin": 4,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 1,
			"date": "2018-02-01"
		},
		{
			"id": "1_5",
			"origin": 1,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 5,
			"date": "2018-02-01"
		},
		{
			"id": "13_24",
			"origin": 13,
			"type": "mensajes",
			"value": 11,
			"course": "2",
			"target": 24,
			"date": "2018-02-01"
		},
		{
			"id": "1_13",
			"origin": 1,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 13,
			"date": "2018-02-01"
		},
		{
			"id": "11_13",
			"origin": 11,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 13,
			"date": "2018-02-01"
		},
		{
			"id": "11_17",
			"origin": 11,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 17,
			"date": "2018-02-01"
		},
		{
			"id": "19_4",
			"origin": 19,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 4,
			"date": "2018-02-01"
		},
		{
			"id": "6_2",
			"origin": 6,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 2,
			"date": "2018-02-01"
		},
		{
			"id": "18_3",
			"origin": 18,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 3,
			"date": "2018-02-01"
		},
		{
			"id": "5_14",
			"origin": 5,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 14,
			"date": "2018-02-01"
		},
		{
			"id": "18_16",
			"origin": 18,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 16,
			"date": "2018-02-01"
		},
		{
			"id": "12_23",
			"origin": 12,
			"type": "visualizacion",
			"value": 3,
			"course": "2",
			"target": 23,
			"date": "2018-02-01"
		},
		{
			"id": "7_11",
			"origin": 7,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 11,
			"date": "2018-02-01"
		},
		{
			"id": "9_3",
			"origin": 9,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 3,
			"date": "2018-02-01"
		},
		{
			"id": "13_20",
			"origin": 13,
			"type": "mensajes",
			"value": 3,
			"course": "2",
			"target": 20,
			"date": "2018-02-01"
		},
		{
			"id": "4_19",
			"origin": 4,
			"type": "visualizacion",
			"value": 3,
			"course": "1",
			"target": 19,
			"date": "2018-02-01"
		},
		{
			"id": "18_13",
			"origin": 18,
			"type": "mensajes",
			"value": 3,
			"course": "1",
			"target": 13,
			"date": "2018-02-01"
		},
		{
			"id": "15_3",
			"origin": 15,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 3,
			"date": "2018-02-01"
		},
		{
			"id": "12_8",
			"origin": 12,
			"type": "visualizacion",
			"value": 11,
			"course": "1",
			"target": 8,
			"date": "2018-02-01"
		},
		{
			"id": "11_21",
			"origin": 11,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 21,
			"date": "2018-02-01"
		},
		{
			"id": "23_11",
			"origin": 23,
			"type": "mensajes",
			"value": 6,
			"course": "1",
			"target": 11,
			"date": "2018-02-01"
		},
		{
			"id": "20_4_2",
			"origin": 20,
			"type": "mensajes",
			"value": 6,
			"course": "1",
			"target": 4,
			"date": "2018-02-01"
		},
		{
			"id": "20_10",
			"origin": 20,
			"type": "mensajes",
			"value": 6,
			"course": "1",
			"target": 10,
			"date": "2018-02-01"
		},
		{
			"id": "11_9",
			"origin": 11,
			"type": "mensajes",
			"value": 6,
			"course": "1",
			"target": 9,
			"date": "2018-02-01"
		},
		{
			"id": "9_19",
			"origin": 9,
			"type": "visualizacion",
			"value": 6,
			"course": "1",
			"target": 19,
			"date": "2018-02-01"
		},
		{
			"id": "20_6",
			"origin": 20,
			"type": "mensajes",
			"value": 6,
			"course": "1",
			"target": 6,
			"date": "2018-02-01"
		},
		{
			"id": "13_18",
			"origin": 13,
			"type": "mensajes",
			"value": 6,
			"course": "1",
			"target": 18,
			"date": "2018-02-01"
		},
		{
			"id": "20_4",
			"origin": 20,
			"type": "mensajes",
			"value": 6,
			"course": "1",
			"target": 4,
			"date": "2018-02-01"
		},
		{
			"id": "6_16",
			"origin": 6,
			"type": "mensajes",
			"value": 6,
			"course": "1",
			"target": 16,
			"date": "2018-02-01"
		},
		{
			"id": "15_8",
			"origin": 15,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 8,
			"date": "2018-02-01"
		},
		{
			"id": "10_18",
			"origin": 10,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 18,
			"date": "2018-02-01"
		},
		{
			"id": "13_11",
			"origin": 13,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 11,
			"date": "2018-02-01"
		},
		{
			"id": "13_10",
			"origin": 13,
			"type": "mensajes",
			"value": 1,
			"course": "1",
			"target": 10,
			"date": "2018-02-01"
		},
		{
			"id": "11_4",
			"origin": 11,
			"type": "mensajes",
			"value": 1,
			"course": "1",
			"target": 4,
			"date": "2018-02-01"
		},
		{
			"id": "14_18",
			"origin": 14,
			"type": "mensajes",
			"value": 1,
			"course": "1",
			"target": 18,
			"date": "2018-02-01"
		},
		{
			"id": "14_13",
			"origin": 14,
			"type": "mensajes",
			"value": 1,
			"course": "1",
			"target": 13,
			"date": "2018-02-01"
		},
		{
			"id": "13_20_2",
			"origin": 13,
			"type": "mensajes",
			"value": 1,
			"course": "1",
			"target": 20,
			"date": "2018-02-01"
		},
		{
			"id": "14_10",
			"origin": 14,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 10,
			"date": "2018-02-01"
		},
		{
			"id": "23_18",
			"origin": 23,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 18,
			"date": "2018-02-01"
		},
		{
			"id": "3_21",
			"origin": 3,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 21,
			"date": "2018-02-01"
		},
		{
			"id": "7_10",
			"origin": 7,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 10,
			"date": "2018-02-01"
		},
		{
			"id": "20_1_2",
			"origin": 20,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 1,
			"date": "2018-02-01"
		},
		{
			"id": "12_13",
			"origin": 12,
			"type": "visualizacion",
			"value": 11,
			"course": "1",
			"target": 13,
			"date": "2018-02-01"
		},
		{
			"id": "20_1",
			"origin": 20,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 1,
			"date": "2018-02-01"
		},
		{
			"id": "10_1_2",
			"origin": 10,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 1,
			"date": "2018-02-01"
		},
		{
			"id": "11_21_2",
			"origin": 11,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 21,
			"date": "2018-02-01"
		},
		{
			"id": "15_17",
			"origin": 15,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 17,
			"date": "2018-02-01"
		},
		{
			"id": "20_23",
			"origin": 20,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 23,
			"date": "2018-02-01"
		},
		{
			"id": "14_23",
			"origin": 14,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 23,
			"date": "2018-02-01"
		},
		{
			"id": "20_1_3",
			"origin": 20,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 1,
			"date": "2018-02-01"
		},
		{
			"id": "1_9_2",
			"origin": 1,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 9,
			"date": "2018-02-01"
		},
		{
			"id": "16_22",
			"origin": 16,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 22,
			"date": "2018-02-01"
		},
		{
			"id": "10_6",
			"origin": 10,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 6,
			"date": "2018-02-01"
		},
		{
			"id": "7_3",
			"origin": 7,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 3,
			"date": "2018-02-01"
		},
		{
			"id": "5_19",
			"origin": 5,
			"type": "visualizacion",
			"value": 11,
			"course": "1",
			"target": 19,
			"date": "2018-02-01"
		},
		{
			"id": "19_15_2",
			"origin": 19,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 15,
			"date": "2018-02-01"
		},
		{
			"id": "9_14",
			"origin": 9,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 14,
			"date": "2018-02-01"
		},
		{
			"id": "14_9",
			"origin": 14,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 9,
			"date": "2018-02-01"
		},
		{
			"id": "21_14",
			"origin": 21,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 14,
			"date": "2018-02-01"
		},
		{
			"id": "13_15",
			"origin": 13,
			"type": "mensajes",
			"value": 11,
			"course": "1",
			"target": 15,
			"date": "2018-02-01"
		}
	]
}