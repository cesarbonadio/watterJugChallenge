# Water Jug Challenge API  🪣🪣

## Overview

This project provides an API to solve the classic Water Jug Challenge using PHP. The challenge involves two jugs with different capacities and the goal is to measure a specific amount of water using the minimum number of steps. The API calculates and returns the steps required to achieve the desired amount of water.

## Installation

To run this project, you need to have the following installed:
- PHP 8 or higher
- Composer 2.6 or higher

### Steps to Install

1. Clone the repository and cd to folder
```console
git clone https://github.com/cesarbonadio/watterJugChallenge.git && cd watterJugChallenge
```
2. Instal dependencies using composer
```console
composer install
```
3. Run server with artisan
```console
php artisan serve
```

### Run tests

For running the test, apply either of the following two commands:

```console
./vendor/bin/pest
```

```console
php artisan test
```

note: tests made with pest.

## API Endpoint

### POST /api/waterjug

This endpoint accepts a JSON payload with the capacities of the two jugs and the desired amount of water. It returns a series of steps to achieve the desired amount.

#### Request Body
| Parameter    | Type | Description  |
| ------------- |:-------------:| ------:|
| x_capacity | int | Capacity of the first jug (bucket X) |
| y_capacity | int | Capacity of the second jug (bucket Y) |
| z_amount_wanted | int | Desired amount of water to be measured (Z) |

##### Example:
```json
{
    "x_capacity": 2,
    "y_capacity": 10,
    "z_amount_wanted": 4 
}
```

#### Response

The response is a JSON array of steps, where each step is an object containing the step number, the current state of the jugs, and the action taken. Each step have a structure like this:

| Field    | Type | Description  |
| ------------- |:-------------:| ------:|
| step | int | The step number |
| bucketX | int | Current amount of water in bucket X |
| bucketY | int | Current amount of water in bucket Y |
| action | string | Description of the action performed |

##### Example:
```json
[
    {
        "step": 1,
        "bucketX": 2,
        "bucketY": 0,
        "action": "Action: Fill bucket x"
    },
    {
        "step": 2,
        "bucketX": 0,
        "bucketY": 2,
        "action": "Action: Transfer from bucket x to bucket y"
    },
    {
        "step": 3,
        "bucketX": 2,
        "bucketY": 2,
        "action": "Action: Fill bucket x"
    },
    {
        "step": 4,
        "bucketX": 0,
        "bucketY": 4,
        "action": "Action: Transfer from bucket x to bucket y"
    }
]
```

## Algorithm Explanation

The Water Jug Challenge can be efficiently solved using the Breadth-First Search (BFS) algorithm. BFS is ideal for this problem because it explores all possible states level by level, ensuring that the solution is found using the minimum number of steps.

### BFS Algorithm Steps

1. Initialize a queue with the initial state (0, 0) and mark it as visited.
2. While the queue is not empty:
    * Dequeue the front state and check if it matches the goal.
    * Generate all possible next states by performing valid actions (fill, empty, transfer).
    * Enqueue the new states and mark them as visited if they haven't been visited before.
3. Return the sequence of actions leading to the goal state.

## Visualization for Non-Technical Audience

To help non-technical individuals understand the process, here is an ASCII representation of the buckets during each step:
```javascript
Step 1: Fill bucket X
X: [XX] Y: [          ]

Step 2: Transfer from bucket X to bucket Y
X: [  ] Y: [XX        ]

Step 3: Fill bucket X
X: [XX] Y: [XX        ]

Step 4: Transfer from bucket X to bucket Y
X: [  ] Y: [XXXX      ]
```

This visual representation shows how water is transferred between the two jugs step by step, making it easier to follow the process.