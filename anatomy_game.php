<?php
// Initialize session to store game state
session_start();

// Initialize game state if not exists
if (!isset($_SESSION['game_state'])) {
    $_SESSION['game_state'] = [
        'score' => 0,
        'level' => 1,
        'current_target' => null,
        'attempts' => 0,
        'feedback' => '',
        'hint_shown' => false,
        'fun_facts_shown' => []
    ];
}

// Ensure fun_facts_shown is initialized
if (!isset($_SESSION['game_state']['fun_facts_shown'])) {
    $_SESSION['game_state']['fun_facts_shown'] = [];
}

// Body parts data with positions, enhanced descriptions and fun facts
$bodyParts = [
    [
        'name' => 'Brain',
        'x' => 50,
        'y' => 15,
        'level' => 1,
        'description' => 'Controls the body and processes information',
        'fun_fact' => 'Your brain uses 20% of your body\'s oxygen and blood, despite being only 2% of your body weight!',
        'color' => '#ff9eb1',
        'icon' => '🧠'
    ],
    [
        'name' => 'Heart',
        'x' => 50,
        'y' => 35,
        'level' => 1,
        'description' => 'Pumps blood throughout the body',
        'fun_fact' => 'Your heart beats about 100,000 times a day, pumping 2,000 gallons of blood!',
        'color' => '#ff5a5a',
        'icon' => '❤️'
    ],
    [
        'name' => 'Lungs',
        'x' => 50,
        'y' => 30,
        'level' => 1,
        'description' => 'Organs for breathing, gas exchange occurs here',
        'fun_fact' => 'If you stretched out your lungs flat, they would cover a tennis court!',
        'color' => '#ffb6c1',
        'icon' => '🫁'
    ],
    [
        'name' => 'Liver',
        'x' => 55,
        'y' => 45,
        'level' => 2,
        'description' => 'Detoxifies and processes nutrients',
        'fun_fact' => 'Your liver performs over 500 different functions and can regenerate itself!',
        'color' => '#a52a2a',
        'icon' => '🧬'
    ],
    [
        'name' => 'Kidneys',
        'x' => 50,
        'y' => 50,
        'level' => 2,
        'description' => 'Filter waste from blood to create urine',
        'fun_fact' => 'Your kidneys filter all your blood about 40 times every day!',
        'color' => '#8b4513',
        'icon' => '🫘'
    ],
    [
        'name' => 'Stomach',
        'x' => 50,
        'y' => 42,
        'level' => 2,
        'description' => 'Digests food with acids and enzymes',
        'fun_fact' => 'Your stomach acid is strong enough to dissolve metal!',
        'color' => '#ffa07a',
        'icon' => '🍽️'
    ],
    [
        'name' => 'Pancreas',
        'x' => 48,
        'y' => 47,
        'level' => 3,
        'description' => 'Produces insulin and digestive enzymes',
        'fun_fact' => 'Your pancreas produces enough digestive juice each day to fill a soda can!',
        'color' => '#ffd700',
        'icon' => '🧪'
    ],
    [
        'name' => 'Spleen',
        'x' => 45,
        'y' => 43,
        'level' => 3,
        'description' => 'Filters blood and helps immune response',
        'fun_fact' => 'Your spleen can store blood and release it when you need it, like during an emergency!',
        'color' => '#800080',
        'icon' => '🔬'
    ],
    [
        'name' => 'Thyroid',
        'x' => 50,
        'y' => 25,
        'level' => 3,
        'description' => 'Regulates metabolism through hormones',
        'fun_fact' => 'Your thyroid controls how quickly you burn calories and how fast your heart beats!',
        'color' => '#ff69b4',
        'icon' => '⚡'
    ],
    [
        'name' => 'Gallbladder',
        'x' => 53,
        'y' => 47,
        'level' => 4,
        'description' => 'Stores bile for fat digestion',
        'fun_fact' => 'Your gallbladder can store enough bile to fill a shot glass!',
        'color' => '#32cd32',
        'icon' => '💧'
    ],
    [
        'name' => 'Appendix',
        'x' => 45,
        'y' => 55,
        'level' => 4,
        'description' => 'Small pouch attached to large intestine',
        'fun_fact' => 'Scientists now believe your appendix stores good bacteria to repopulate your gut after illness!',
        'color' => '#ff4500',
        'icon' => '🔍'
    ],
    [
        'name' => 'Adrenal Glands',
        'x' => 50,
        'y' => 48,
        'level' => 4,
        'description' => 'Produce stress hormones',
        'fun_fact' => 'Your adrenal glands produce adrenaline that can make your heart beat 3 times faster in seconds!',
        'color' => '#ffa500',
        'icon' => '⚡'
    ],
    [
        'name' => 'Hypothalamus',
        'x' => 50,
        'y' => 17,
        'level' => 5,
        'description' => 'Controls body temperature and hunger',
        'fun_fact' => 'Your hypothalamus is about the size of an almond but controls your body temperature, hunger, thirst, and sleep!',
        'color' => '#00bfff',
        'icon' => '🌡️'
    ],
    [
        'name' => 'Pituitary',
        'x' => 50,
        'y' => 18,
        'level' => 5,
        'description' => 'Master gland controlling hormones',
        'fun_fact' => 'Your pituitary gland is the size of a pea but produces hormones that control growth, blood pressure, and more!',
        'color' => '#ff1493',
        'icon' => '🔮'
    ],
    [
        'name' => 'Thalamus',
        'x' => 50,
        'y' => 16,
        'level' => 5,
        'description' => 'Relays sensory information to the brain',
        'fun_fact' => 'Your thalamus acts like a switchboard operator, routing sensory messages to the right parts of your brain!',
        'color' => '#4169e1',
        'icon' => '📡'
    ]
];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'select_part' && isset($_POST['part_name'])) {
            handleBodyPartSelection($_POST['part_name'], $bodyParts);
        } elseif ($_POST['action'] === 'next_level') {
            goToNextLevel();
        } elseif ($_POST['action'] === 'reset_game') {
            resetGame();
        }
    }
}

// If no target is set, choose one
if ($_SESSION['game_state']['current_target'] === null) {
    setNewTarget($bodyParts);
}

// Function to set a new target body part
function setNewTarget($bodyParts) {
    $level = $_SESSION['game_state']['level'];
    $availableParts = array_filter($bodyParts, function($part) use ($level) {
        return $part['level'] <= $level;
    });

    $targetIndex = array_rand($availableParts);
    $_SESSION['game_state']['current_target'] = $availableParts[$targetIndex];
    $_SESSION['game_state']['attempts'] = 0;
    $_SESSION['game_state']['feedback'] = '';
    $_SESSION['game_state']['hint_shown'] = false;
}

// Function to handle body part selection
function handleBodyPartSelection($selectedPartName, $bodyParts) {
    $selectedPart = null;
    foreach ($bodyParts as $part) {
        if ($part['name'] === $selectedPartName) {
            $selectedPart = $part;
            break;
        }
    }

    if ($selectedPart) {
        $_SESSION['game_state']['attempts']++;

        if ($selectedPart['name'] === $_SESSION['game_state']['current_target']['name']) {
            // Correct answer
            $_SESSION['game_state']['score'] += max(5 - $_SESSION['game_state']['attempts'] + 1, 1) * $_SESSION['game_state']['level'];
            $_SESSION['game_state']['feedback'] = 'correct';
            $_SESSION['game_state']['hint_shown'] = true;

            // Store the fun fact as shown
            if (!in_array($selectedPart['name'], $_SESSION['game_state']['fun_facts_shown'])) {
                $_SESSION['game_state']['fun_facts_shown'][] = $selectedPart['name'];
            }
        } else {
            // Incorrect answer
            $_SESSION['game_state']['feedback'] = 'incorrect';

            // Show hint after 1 incorrect attempt to make it more fun
            if ($_SESSION['game_state']['attempts'] >= 1) {
                $_SESSION['game_state']['hint_shown'] = true;
            }
        }
    }
}

// Function to go to the next level
function goToNextLevel() {
    if ($_SESSION['game_state']['feedback'] === 'correct') {
        if ($_SESSION['game_state']['level'] < 5) {
            $_SESSION['game_state']['level']++;
        }
        setNewTarget($GLOBALS['bodyParts']);
    }
}

// Function to reset the game
function resetGame() {
    $_SESSION['game_state'] = [
        'score' => 0,
        'level' => 1,
        'current_target' => null,
        'attempts' => 0,
        'feedback' => '',
        'hint_shown' => false,
        'fun_facts_shown' => []
    ];
    setNewTarget($GLOBALS['bodyParts']);
}

// Get body parts for current level
function getBodyPartsForCurrentLevel($bodyParts) {
    $level = $_SESSION['game_state']['level'];
    return array_filter($bodyParts, function($part) use ($level) {
        return $part['level'] <= $level;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Human Anatomy</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Colors - Monochromatic with minimal accent colors */
            --bg-color: #f9fafb;
            --text-color: #1f2937;
            --text-secondary: #6b7280;
            --accent-color: #3b82f6;
            --correct-color: #10b981;
            --incorrect-color: #ef4444;
            --neutral-color: #9ca3af;
            --border-color: #f3f4f6;
            --card-bg: #ffffff;

            /* Typography - Based on 1.2 ratio for harmony */
            --font-size-xs: 0.694rem;  /* 11.1px */
            --font-size-sm: 0.833rem;  /* 13.3px */
            --font-size-md: 1rem;      /* 16px - base */
            --font-size-lg: 1.2rem;    /* 19.2px */
            --font-size-xl: 1.44rem;   /* 23px */

            /* Spacing - Based on 8px grid */
            --space-3xs: 0.125rem; /* 2px */
            --space-2xs: 0.25rem;  /* 4px */
            --space-xs: 0.5rem;    /* 8px */
            --space-sm: 0.75rem;   /* 12px */
            --space-md: 1rem;      /* 16px */
            --space-lg: 1.5rem;    /* 24px */
            --space-xl: 2rem;      /* 32px */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            height: 100%;
            font-size: 16px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100%;
            line-height: 1.5;
            padding: var(--space-md) var(--space-xs);
            font-size: var(--font-size-md);
            width: 100%;
            margin: 0;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }

        header {
            width: 100%;
            text-align: center;
            margin-bottom: var(--space-md);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-weight: 600;
            font-size: var(--font-size-xl);
            margin-bottom: var(--space-2xs);
            letter-spacing: -0.01em;
            color: var(--text-color);
        }

        .subtitle {
            font-size: var(--font-size-sm);
            color: var(--text-secondary);
            font-weight: 400;
        }

        .game-container {
            width: 100%;
            max-width: 1000px;
            display: flex;
            flex-direction: column;
            gap: var(--space-md);
            background-color: var(--card-bg);
            border-radius: 2px;
            padding: var(--space-lg);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
            margin: 0 auto;
        }

        .game-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-xs) var(--space-sm);
            border-bottom: 1px solid var(--border-color);
            background-color: var(--card-bg);
        }

        .score-display, .level-display {
            font-size: var(--font-size-sm);
            font-weight: 500;
            color: var(--text-secondary);
        }

        .human-figure {
            position: relative;
            width: 100%;
            height: 70vh;
            min-height: 550px;
            background-color: var(--card-bg);
            overflow: hidden;
            background-image: url('https://cdn.pixabay.com/photo/2013/07/13/11/44/human-158385_960_720.png');
            background-position: center;
            background-repeat: no-repeat;
            background-size: contain;
            margin: var(--space-xs) 0;
        }

        .body-part {
            position: absolute;
            font-weight: 500;
            font-size: var(--font-size-xs);
            cursor: pointer;
            transition: all 0.15s ease;
            background-color: var(--card-bg);
            color: var(--text-color);
            padding: var(--space-2xs) var(--space-xs);
            border-radius: 2px;
            border-bottom: 1px solid var(--border-color);
            z-index: 1;
        }

        .body-part:hover {
            background-color: var(--accent-color);
            color: white;
            z-index: 2;
        }

        .body-part.correct {
            background-color: var(--correct-color);
            color: white;
        }

        .body-part.incorrect {
            background-color: var(--incorrect-color);
            color: white;
        }

        .task-panel {
            background-color: var(--card-bg);
            padding: var(--space-sm);
            border-left: 1px solid var(--border-color);
        }

        .task-prompt {
            font-size: var(--font-size-sm);
            font-weight: 500;
            margin-bottom: var(--space-sm);
            color: var(--text-color);
        }

        .hint {
            font-size: var(--font-size-xs);
            background-color: var(--bg-color);
            padding: var(--space-xs);
            margin-top: var(--space-sm);
            color: var(--text-secondary);
            border-left: 2px solid var(--neutral-color);
        }

        .fun-fact {
            font-size: var(--font-size-xs);
            background-color: var(--bg-color);
            padding: var(--space-xs);
            margin-top: var(--space-sm);
            color: var(--text-color);
            border-left: 2px solid var(--accent-color);
        }

        .feedback {
            height: var(--space-md);
            font-weight: 500;
            font-size: var(--font-size-xs);
            margin-top: var(--space-sm);
        }

        button {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: var(--space-xs) var(--space-sm);
            border-radius: 2px;
            font-weight: 500;
            font-size: var(--font-size-xs);
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        button:hover {
            background-color: #2563eb;
        }

        button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .info-controls-container {
            display: flex;
            flex-direction: row;
            gap: var(--space-md);
            width: 100%;
        }

        .task-panel {
            flex: 1;
            min-width: 0;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: var(--space-xs);
            justify-content: flex-start;
            align-items: stretch;
            width: 100px;
        }

        .button-container button {
            width: 100%;
        }

        .rules-toggle {
            background-color: transparent;
            color: var(--text-secondary);
            border: none;
            padding: var(--space-2xs) var(--space-xs);
            margin-top: var(--space-xs);
            font-size: var(--font-size-xs);
            width: auto;
            align-self: center;
            text-decoration: underline;
        }

        .rules-toggle:hover {
            color: var(--accent-color);
            background-color: transparent;
        }

        .rules-panel {
            display: none;
            background-color: var(--bg-color);
            border-left: 2px solid var(--border-color);
            padding: var(--space-sm);
            margin-top: var(--space-sm);
            font-size: var(--font-size-xs);
            color: var(--text-secondary);
            width: 100%;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .rules-panel.show {
            display: block;
        }

        .rules-panel h2 {
            font-size: var(--font-size-sm);
            font-weight: 500;
            margin-bottom: var(--space-xs);
            color: var(--text-color);
        }

        .rules-panel ol {
            padding-left: var(--space-md);
            margin-bottom: var(--space-xs);
        }

        .rules-panel li {
            margin-bottom: var(--space-2xs);
        }

        .footer {
            margin-top: var(--space-lg);
            padding-top: var(--space-sm);
            border-top: 1px solid var(--border-color);
            font-size: var(--font-size-xs);
            color: var(--neutral-color);
            text-align: center;
        }

        .footer-note {
            margin-top: var(--space-2xs);
            font-size: calc(var(--font-size-xs) * 0.9);
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .info-controls-container {
                flex-direction: column;
            }

            .button-container {
                flex-direction: row;
                width: 100%;
                margin-top: var(--space-xs);
                justify-content: flex-end;
            }

            .button-container form {
                width: auto;
            }

            .button-container button {
                width: auto;
            }

            .rules-panel {
                padding: var(--space-xs);
                max-width: 100%;
            }

            .human-figure {
                height: 60vh;
                min-height: 450px;
            }
        }
    </style>
</head>
<body>
    <div class="game-container">
        <header>
            <h1>Human Anatomy</h1>
            <div class="subtitle">Learn about the human body</div>
            <button type="button" id="rules-btn" class="rules-toggle">Show Rules</button>
            <div id="rules-panel" class="rules-panel">
                <h2>How to Play</h2>
                <ol>
                    <li>Find the body part mentioned in the prompt</li>
                    <li>Click on the correct body part to score points</li>
                    <li>Higher levels introduce more complex body parts</li>
                    <li>After a correct answer, click "Next Level" to advance</li>
                    <li>Learn fascinating facts about human anatomy as you play</li>
                </ol>
                <p>The game has 5 levels with increasingly challenging body parts to identify.</p>
            </div>
        </header>

        <div class="game-controls">
            <div class="level-display">Level: <?php echo $_SESSION['game_state']['level']; ?></div>
            <div class="score-display">Score: <?php echo $_SESSION['game_state']['score']; ?></div>
        </div>

        <div class="human-figure">
            <?php foreach (getBodyPartsForCurrentLevel($bodyParts) as $part): ?>
                <form method="post" style="position: absolute; left: <?php echo $part['x']; ?>%; top: <?php echo $part['y']; ?>%;">
                    <input type="hidden" name="action" value="select_part">
                    <input type="hidden" name="part_name" value="<?php echo $part['name']; ?>">
                    <button type="submit" class="body-part<?php
                        if ($_SESSION['game_state']['feedback'] === 'correct' && $part['name'] === $_SESSION['game_state']['current_target']['name']) {
                            echo ' correct';
                        } elseif ($_SESSION['game_state']['feedback'] === 'incorrect' && $part['name'] === $_POST['part_name']) {
                            echo ' incorrect';
                        }
                    ?>"><?php echo $part['name']; ?></button>
                </form>
            <?php endforeach; ?>
        </div>

        <div class="info-controls-container">
            <div class="task-panel">
                <div class="task-prompt">
                    Find the <?php echo strtolower($_SESSION['game_state']['current_target']['name']); ?>
                </div>

                <?php if ($_SESSION['game_state']['hint_shown']): ?>
                    <div class="hint">
                        <?php echo $_SESSION['game_state']['current_target']['description']; ?>
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['game_state']['feedback'] === 'correct'): ?>
                    <div class="fun-fact">
                        <?php echo $_SESSION['game_state']['current_target']['fun_fact']; ?>
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['game_state']['feedback']): ?>
                <div class="feedback" style="color: <?php echo $_SESSION['game_state']['feedback'] === 'correct' ? 'var(--correct-color)' : 'var(--incorrect-color)'; ?>">
                    <?php echo $_SESSION['game_state']['feedback'] === 'correct' ? 'Correct' : 'Incorrect'; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="button-container">
                <form method="post">
                    <input type="hidden" name="action" value="reset_game">
                    <button type="submit">Reset</button>
                </form>

                <form method="post">
                    <input type="hidden" name="action" value="next_level">
                    <button type="submit" <?php echo $_SESSION['game_state']['feedback'] !== 'correct' ? 'disabled' : ''; ?>>
                        Next Level
                    </button>
                </form>
            </div>
        </div>
        <footer class="footer">
            <p>&copy; <?php echo date('Y'); ?> Human Anatomy Game. All rights reserved.</p>
            <p class="footer-note">Created for educational purposes. Body illustrations from public domain sources.</p>
        </footer>
    </div>

    <script>
        document.getElementById('rules-btn').addEventListener('click', function() {
            const rulesPanel = document.getElementById('rules-panel');
            const rulesBtn = document.getElementById('rules-btn');

            if (rulesPanel.classList.contains('show')) {
                rulesPanel.classList.remove('show');
                rulesBtn.textContent = 'Show Rules';
            } else {
                rulesPanel.classList.add('show');
                rulesBtn.textContent = 'Hide Rules';
            }
        });
    </script>
</body>
</html>
