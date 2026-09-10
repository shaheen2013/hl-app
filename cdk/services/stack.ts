import type {
	FargateProps,
	EnvConfig,
	StageType,
} from "../../../hotelinking_gateway/fargate/config/types";
import path = require("node:path");
import type { Construct } from "constructs";
import { FargateStack } from "../../../hotelinking_gateway/fargate/bin/app";

const serviceName = "app";

export const generateAppFargateStack = (
	app: Construct,
	{ envConfig, stage }: { envConfig: EnvConfig; stage: StageType },
) => {
	const appFargateProps: FargateProps = {
		...envConfig,
		stage: stage,
		service: serviceName,
		docker: {
			imageDir: path.join(__dirname, "../../backend"),
		},
		tags: {
			team: "app",
			project: serviceName,
		},
		app: {
			autoscale: {
				cpu: envConfig.autoscale.albService.cpu,
				memoryLimitMiB: envConfig.autoscale.albService.memoryLimitMiB,
				targetValue: envConfig.autoscale.albService.targetValue,
			},
			command: [
				'sh',
				'-c',
				'mkdir -p /app/logs && touch /app/logs/app.log && (tail -f /app/logs/app.log &) && frankenphp run --config /etc/frankenphp/Caddyfile',
			]
		},
		subnets: {
			cidr1_public: envConfig.subnets.cidr1_public,
			cidr2_public: envConfig.subnets.cidr2_public,
		},
		policies: []
	};

	return new FargateStack(
		app,
		`AppFargateStack-${stage}`,
		appFargateProps,
	);
};
