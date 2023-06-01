<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------
    public $validation_add = [
        'correo' => [
            'label' => 'Correo',
            'rules' => 'required|valid_email|is_unique[usuario.correo]',
            'errors' => [
                'required' => 'El correo es un campo obligatorio.',
                'valid_email' => 'Por favor ingrese un Correo valido',
                'is_unique' => 'El correo ingresado ya pertenece al sistema',
            ]
        ],
        'clave' =>  [
            'label' => "Clave",
            'rules' => 'required|min_length[5]',
            'errors' => [
                'required' => 'La clave es un campo obligatorio.',
                'min_length' => 'Por favor ingrese una Clave mayor o igual a 6 digitos',
            ]
        ],
        'nombre'  => [
            'label' => "Nombre",
            'rules' => 'required',
            'errors' => [
                'required' => "El nombre es un campo obligatorio"
            ]
        ],
        'apellido'  => [
            'label' => "Apellido",
            'rules' => 'required',
            'errors' => [
                'required' => "El Apellido es un campo obligatorio"
            ]
        ],
        'empresa'  => [
            'label' => "Empresa",
            'rules' => 'required',
            'errors' => [
                'required' => "La Empresa es un campo obligatorio"
            ]
        ],
        'telefono' =>  [
            'label' => "Telefono",
            'rules' => 'integer',
            'errors' => [
                'integer' => "El número de telefono debe contener sólo caracteres numericos"
            ]
        ]
    ];
    public $validation_edit_user = [
        'correo' => [
            'label' => 'Correo',
            'rules' => 'required|valid_email',
            'errors' => [
                'required' => 'El correo es un campo obligatorio.',
                'valid_email' => 'Por favor ingrese un Correo valido',
            ]
        ],
        'nombre'  => [
            'label' => "Nombre",
            'rules' => 'required',
            'errors' => [
                'required' => "El nombre es un campo obligatorio"
            ]
        ],
        'apellido'  => [
            'label' => "Apellido",
            'rules' => 'required',
            'errors' => [
                'required' => "El Apellido es un campo obligatorio"
            ]
        ],
        'telefono' =>  [
            'label' => "Telefono",
            'rules' => 'integer',
            'errors' => [
                'integer' => "El número de telefono debe contener sólo caracteres numericos"
            ]
        ]
    ];
    public $validation_menu = [

        'nombre' => [
            'label' => 'Nombre',
            'rules' => ['required', 'max_length[150]'],
            'errors' => [
                'required' => 'No se especificó %s',
                'max_length' => '%s supera los caracteres permitidos (150)',
            ]

        ],
        'id_menu_padre' => [
            'label' => 'Submenu',
            'rules' => ['required', 'is_natural'],
            'errors' => [
                'required' => 'No se especificó %s',
                'is_natural' => '%s inválido',
            ],
        ],
        'orden' => [
            'label' => 'Orden',
            'rules' => ['required', 'is_natural'],
            'errors' => [
                'required' => 'No se especificó %s',
                'is_natural' => '%s inválido'
            ],
        ],
        'ruta' => [
            'label' => 'URL',
            'rules' => ['max_length[150]'],
            'errors' => [
                'max_length' => '%s supera los caracteres permitidos (150)',
                //'alpha_dash' => '%s contiene caracteres inválidos' => GENERA ERROR SI SE USA "/"
            ],
        ],
        'icono' => [
            'label' => '',
            'rules' => ['max_length[50]', 'alpha_dash'],
            'errors' => [
                'max_length' => '%s supera los caracteres permitidos (50)',
                'alpha_dash' => '%s contiene caracteres inválidos'
            ],
        ]
    ];

    public $validation_form = [

        'tipo_plan' => [
            'label' => 'Tipo de Formulario',
            'rules' => ['required', 'is_unique[estadisticas_tipo.nombre]'],
            'errors' => [
                'required' => 'No se especificó %s',
                'is_unique' => 'El nombre del Formulario ingresado ya existe.'
            ]

        ]
    ];

    public $validation_permiso = [
        'nombre' => [
            'label' => 'Nombre',
            'rules' => ['required', 'max_length[90]'],
            'errors' => [
                'required' => 'No se especificó %s',
                'max_length' => '%s supera los caracteres permitidos (90)',
            ]

        ],
        'id_permiso_padre' => [
            'label' => 'Subpermiso',
            'rules' => ['required', 'is_natural'],
            'errors' => [
                'required' => 'No se especificó %s',
                'is_natural' => '%s inválido',
            ],
        ],
        'orden' => [
            'label' => 'Orden',
            'rules' => ['required', 'is_natural'],
            'errors' => [
                'required' => 'No se especificó %s',
                'is_natural' => '%s inválido'
            ],
        ],
        'tipo_modulo' => [
            'label' => 'Tipo Modulo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó %s',
            ],
        ],
    ];

    public $validation_tarjeta = [
        'fecha_deteccion' => [
            'label' => 'Fecha de Detección',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la fecha de detección',
            ]
        ],
        'tipo_obs' => [
            'label' => 'Tipo de Observación',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el tipo de observación',
            ]
        ],
        // El observador no es requerido
        // La descripción no es requerida
        'proyecto' => [
            'label' => 'Proyecto',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el proyecto',
            ]
        ],
        // El módulo no es requerido
        /* La estación de bombeo tanto como el sistema de oleoducto puede ser requerido o no */
        /* 'estacion_bombeo' => [
            'label' => 'Estación de Bombeo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la estación de bombeo',
            ]
        ],
        'sistema_oleoducto' => [
            'label' => 'Sistema de Oleoducto',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el sistema de oleoducto',
            ]
        ], */
    ];

    public $validation_hallazgo = [
        'hallazgo' => [
            'label' => 'Hallazgo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el hallazgo',
            ]
        ],
        'accion_recomendacion' => [
            'label' => 'Plan de Acción',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el plan de acción',
            ]
        ],
        'clasificacion' => [
            'label' => 'Clasificación',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la clasificación',
            ]
        ],
        'tipo' => [
            'label' => 'Tipo de Hallazgo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el tipo de hallazgo',
            ]
        ],
        'matriz_riesgo' => [
            'label' => 'Riesgo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el riesgo',
            ]
        ],
        'contratista' => [
            'label' => 'Contratista',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la contratista',
            ]
        ],
        'responsable' => [
            'label' => 'Responsable',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el responsable',
            ]
        ],
        'fecha_cierre' => [
            'label' => 'Fecha de Cierre',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la fecha de cierre',
            ]
        ],
    ];

    public $validation_obs_positiva = [
        'hallazgo' => [
            'label' => 'Descripción de lo observado',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el hallazgo',
            ]
        ],
        'clasificacion' => [
            'label' => 'Clasificación',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la clasificacion',
            ]
        ],
        'contratista' => [
            'label' => 'Contratista',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la contratista',
            ]
        ],
    ];

    public $validation_estadistica = [
        'contratista' => [
            'label' => 'Contratista ',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la contratista',
            ]
        ],
        'periodo' => [
            'label' => 'Periodo ',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el periodo',
            ]
        ],
        'proyecto' => [
            'label' => 'Proyecto',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el proyecto',
            ]
        ],
        'modulo' => [
            'label' => 'Modulo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el modulo',
            ]
        ],
        'estacion' => [
            'label' => 'Estacion',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó la estacion',
            ]
        ],
        /* 'sistema' => [
            'label' => 'Sistema',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el sistema',
            ]
        ], */
    ];

    public $validation_permission = [
        'nombre' => [
            'label' => 'nombre',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el nombre del permiso',
            ]
        ],
        'id_permiso_padre' => [
            'label' => 'subpermiso',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el SubPermiso',
            ]
        ],
        'tipo_modulo' => [
            'label' => 'tipo_modulo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el tipo de modulo',
            ]
        ]
    ];

    /* Validar Auditorías */
    public $validation_subtitle_aud = [
        'subtitle' => [
            'label' => 'subtitle',
            'rules' => ['required'],
            'errors' => [
                'required' => 'Los titulos de cada bloque no puede ir vacío',
            ]
        ],
    ];
    public $validation_title_aud = [
        'nombre' => [
            'label' => 'nombre',
            'rules' => ['required'],
            'errors' => [
                'required' => 'No se especificó el nombre de la auditoría',
            ]
        ],
    ];
    public $validation_preguntas_aud = [
        'preguntas' => [
            'label' => 'preguntas',
            'rules' => ['required'],
            'errors' => [
                'required' => 'Las preguntas no pueden estar vacías',
            ]
        ],
    ];
    public $validation_aud_control = [
        'contratista' => [
            'label' => 'contratista',
            'rules' => ['required'],
            'errors' => [
                'required' => 'La contratista es requerida',
            ]
        ],
        'supervisor_responsable' => [
            'label' => 'supervisor_responsable',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El supervisor es requerido',
            ]
        ],
        'cant_personal' => [
            'label' => 'cant_personal',
            'rules' => ['required'],
            'errors' => [
                'required' => 'La cantidad del personal es requerido',
            ]
        ],
        'num_informe' => [
            'label' => 'num_informe',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El N° de Informe es requerido',
            ]
        ],
        'proyecto' => [
            'label' => 'proyecto',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El proyecto es requerido',
            ]
        ],
    ];
    public $validation_aud_vehicular = [
        'equipo' => [
            'label' => 'equipo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El Equipo es requerido',
            ]
        ],
        'conductor' => [
            'label' => 'conductor',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El conductor es requerido',
            ]
        ],
        'num_interno' => [
            'label' => 'num_interno',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El N° Interno es requerido',
            ]
        ],
        'marca' => [
            'label' => 'marca',
            'rules' => ['required'],
            'errors' => [
                'required' => 'La marca es requerida',
            ]
        ],
        'modelo' => [
            'label' => 'modelo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El modelo es requerido',
            ]
        ],
        'patente' => [
            'label' => 'patente',
            'rules' => ['required'],
            'errors' => [
                'required' => 'La patente es requerida',
            ]
        ],
        'titular' => [
            'label' => 'titular',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El titular es requerido',
            ]
        ],
        'fecha' => [
            'label' => 'fecha',
            'rules' => ['required'],
            'errors' => [
                'required' => 'La fecha es requerida',
            ]
        ],
        'hora' => [
            'label' => 'hora',
            'rules' => ['required'],
            'errors' => [
                'required' => 'La hora es requerida',
            ]
        ],
        'proyecto' => [
            'label' => 'proyecto',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El proyecto es requerido',
            ]
        ],
        'resultado_inspeccion' => [
            'label' => 'resultado_inspeccion',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El Resultado de la Inspección es requerido',
            ]
        ]
    ];
    public $validation_aud_plan = [
        'hallazgo' => [
            'label' => 'hallazgo',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El Hallazgo es requerido',
            ]
        ],
        'plan_accion' => [
            'label' => 'plan_accion',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El Plan de Acción es requerido',
            ]
        ],
        'efecto_impacto' => [
            'label' => 'efecto_impacto',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El Efecto / Impacto es requerido',
            ]
        ],
        'contratista' => [
            'label' => 'contratista',
            'rules' => ['required'],
            'errors' => [
                'required' => 'La Contratista es requerida',
            ]
        ],
        'responsable' => [
            'label' => 'responsable',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El Responsable es requerido',
            ]
        ],
        /* 'significancia' => [
            'label' => 'significancia',
            'rules' => ['required'],
            'errors' => [
                'required' => 'El Riesgo es requerido',
            ]
        ], */
        'fecha_cierre' => [
            'label' => 'fecha_cierre',
            'rules' => ['required'],
            'errors' => [
                'required' => 'La Fecha de Cierre es requerida',
            ]
        ],
    ];
}
