<?php

namespace App\Services;

use App\Util\ResponseFormater;

/**
 * Class WatterJugService
 *
 * This service provides methods to solve the water jug problem using the Breadth-First Search (BFS) algorithm.
 */
class WatterJugService
{
    const ERROR_NO_SOLUTION_MESSAGE = 'No solution can be found';


     /**
     * Formats the action taken at each step of the water jug problem solution.
     *
     * @param int $step The step number.
     * @param array $state The state of the buckets [bucketX, bucketY].
     * @param string $action The description of the action taken.
     * @return array The formatted action.
     */
    private static function formatAction($step, $state, $action): array {
        return [
            "step" => $step,
            "bucketX" => $state[0],
            "bucketY" => $state[1],
            "action" => $action
        ];
    }

    /**
     * Understands and formats the actions taken to solve the water jug problem from the given path.
     *
     * @param array $path The sequence of states leading to the solution.
     * @return array The list of formatted actions taken to reach the solution.
     */
    private static function understandActions(array $path): array {
        $actions = [];
        for ($i = 0; $i < count($path); $i++) {
            $current = $path[$i];
            if ($i > 0) {
                $previous = $path[$i - 1];
                // Determine the action
                if ($current[0] > $previous[0] && $current[1] == $previous[1]) {
                    $actions[] = self::formatAction($i, $current, "Fill bucket x");
                } elseif ($current[1] > $previous[1] && $current[0] == $previous[0]) {
                    $actions[] = self::formatAction($i, $current, "Fill bucket y");
                } elseif ($current[0] < $previous[0] && $current[1] == $previous[1]) {
                    $actions[] = self::formatAction($i, $current, "Empty bucket x");
                } elseif ($current[1] < $previous[1] && $current[0] == $previous[0]) {
                    $actions[] = self::formatAction($i, $current, "Empty bucket y");
                } elseif ($current[0] > $previous[0] && $current[1] < $previous[1]) {
                    $actions[] = self::formatAction($i, $current, "Transfer from bucket y to bucket x");
                } elseif ($current[1] > $previous[1] && $current[0] < $previous[0]) {
                    $actions[] = self::formatAction($i, $current, "Transfer from bucket x to bucket y");
                }
            }
        }
        return $actions;
    }

    /**
     * Solves the water jug problem using Breadth-First Search (BFS) algorithm.
     *
     * @param int $a The capacity of bucket x.
     * @param int $b The capacity of bucket y.
     * @param int $target The target amount of water to measure.
     * @return array|string The sequence of actions to reach the target amount or "no solution" if it is not possible.
     */
    private static function waterJugBFS(
        int $a,
        int $b,
        int $target
    ): array 
    {
        $visited = [];
        $parent = [];

        $q = new \SplQueue();
        $q->enqueue([0, 0]);
        $parent["0,0"] = null;

        while (!$q->isEmpty()) {
            $u = $q->dequeue();

            if (in_array($u, $visited)) {
                continue;
            }

            $visited[] = $u;

            if ($u[0] == $target || $u[1] == $target) {
                $path = [];
                while ($u !== null) {
                    $path[] = $u;
                    $u = $parent[implode(',', $u)];
                }
                $path = array_reverse($path);
                return self::understandActions($path);
            }

            if (!in_array([$a, $u[1]], $visited)) {
                $q->enqueue([$a, $u[1]]);
                $parent["$a,{$u[1]}"] = $u;
            }

            if (!in_array([$u[0], $b], $visited)) {
                $q->enqueue([$u[0], $b]);
                $parent["{$u[0]},$b"] = $u;
            }

            if (!in_array([0, $u[1]], $visited)) {
                $q->enqueue([0, $u[1]]);
                $parent["0,{$u[1]}"] = $u;
            }

            if (!in_array([$u[0], 0], $visited)) {
                $q->enqueue([$u[0], 0]);
                $parent["{$u[0]},0"] = $u;
            }

            $transfer = min($u[0], $b - $u[1]);
            $new_state = [$u[0] - $transfer, $u[1] + $transfer];
            if (!in_array($new_state, $visited)) {
                $q->enqueue($new_state);
                $parent[implode(',', $new_state)] = $u;
            }

            $transfer = min($u[1], $a - $u[0]);
            $new_state = [$u[0] + $transfer, $u[1] - $transfer];
            if (!in_array($new_state, $visited)) {
                $q->enqueue($new_state);
                $parent[implode(',', $new_state)] = $u;
            }
        }

        return self::ERROR_NO_SOLUTION_MESSAGE;
    }

    /**
     * Computes the sequence of actions to solve the water jug problem.
     *
     * @param int $x_capacity The capacity of bucket x.
     * @param int $y_capacity The capacity of bucket y.
     * @param int $z_amount_wanted The target amount of water to measure.
     * @return array The sequence of actions to reach the target amount.
     */
    public static function compute(
        int $x_capacity,
        int $y_capacity,
        int $z_amount_wanted
    ): array
    {
        // Validate input
        if ($z_amount_wanted > $x_capacity && $z_amount_wanted > $y_capacity) {
            return ResponseFormater::response(
                'No solution possible. The amount wanted can\'t be greater than the capacity of both two jugs',
                422
            );
        }

        // Compute result
        $computed_result = self::waterJugBFS($x_capacity, $y_capacity, $z_amount_wanted);

        // Check if computation was successful
        if ($computed_result === self::ERROR_NO_SOLUTION_MESSAGE) {
            return ResponseFormater::response(
                'No solution possible. Computationally, it was impossible to find the result for some reason',
                422
            );
        }

        return ResponseFormater::response(
            'result found successfully',
            200,
            $computed_result,
            ['number_of_steps' => count($computed_result)]
        );
    }
}
